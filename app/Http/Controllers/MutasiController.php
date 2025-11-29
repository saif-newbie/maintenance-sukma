<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Penduduk;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MutasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function availableDusuns()
    {
        $dusuns = KartuKeluarga::distinct()
            ->whereNotNull('dusun')
            ->where('dusun', '!=', '')
            ->orderBy('dusun')
            ->pluck('dusun');

        return response()->json($dusuns);
    }

    public function index(Request $request)
    {
        // Retrieve all mutasi data with relasi penduduk dan kartu keluarga
        // Order by creation date to maintain insertion sequence (oldest first)
        // This ensures new mutations appear at the end of the sequence
        $query = Mutasi::with('penduduk.kartuKeluarga')
            ->orderBy('created_at', 'asc') // Primary sorting: oldest first
            ->orderBy('tanggal_kejadian', 'asc'); // Secondary sorting: chronological

        // Apply dusun filter if provided
        if ($request->has('dusun') && !empty($request->dusun)) {
            $query->whereHas('penduduk.kartuKeluarga', function($q) use ($request) {
                $q->where('dusun', $request->dusun);
            });
        }

        $mutasi = $query->get();

        // Get available dusuns for filter
        $availableDusuns = KartuKeluarga::distinct()
            ->whereNotNull('dusun')
            ->where('dusun', '!=', '')
            ->orderBy('dusun')
            ->pluck('dusun');

        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            // Return only the table body content for AJAX requests
            return view('partials.mutasi_table', compact('mutasi'))->render();
        }

        return view('mutasi.index', compact('mutasi', 'availableDusuns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Get all penduduk yang masih HIDUP dan belum di-soft delete untuk dropdown
        // Filter ini penting untuk memastikan hanya penduduk aktif yang bisa dipilih untuk MENINGGAL/PINDAH
        $penduduk = Penduduk::with('kartuKeluarga')
            ->where('status', 'HIDUP')
            ->orderBy('nama')
            ->get();

        // Get all KK dengan head of family untuk dropdown new penduduk (LAHIR/DATANG)
        $kartuKeluarga = KartuKeluarga::with(['penduduk' => function($query) {
            $query->where('hubungan_keluarga', 'Kepala Keluarga');
        }])->orderBy('no_kk')->get();

        // Get available dusuns for dropdown
        $availableDusuns = KartuKeluarga::distinct()
            ->whereNotNull('dusun')
            ->where('dusun', '!=', '')
            ->orderBy('dusun')
            ->pluck('dusun');

        return view('mutasi.create', compact('penduduk', 'kartuKeluarga', 'availableDusuns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * Logic Implementation:
     * A. Case LAHIR (Birth) or DATANG (Arrival):
     *    - Create new Penduduk with status 'HIDUP'
     *    - Assign to existing or new KartuKeluarga
     * B. Case MENINGGAL (Death) or PINDAH (Moving Out):
     *    - Find existing Penduduk and update status column
     * C. Always save log record to mutasis table
     */
    public function store(Request $request)
    {
        // VALIDASI DINAMIS berdasarkan jenis mutasi
        $rules = [
            'jenis_mutasi' => 'required|in:LAHIR,MENINGGAL,DATANG,PINDAH',
            'tanggal_kejadian' => 'required|date',
            'lokasi_detail' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ];

        // Validasi khusus untuk LAHIR atau DATANG (perlu data penduduk baru)
        if (in_array($request->jenis_mutasi, ['LAHIR', 'DATANG'])) {
            $rules = array_merge($rules, [
                'arrival_mode' => 'required|in:individu,keluarga',
            ]);

            if ($request->jenis_mutasi === 'LAHIR' || $request->arrival_mode === 'individu') {
                // Individual arrival or birth validation
                $rules = array_merge($rules, [
                    'nik' => 'required|string|size:16|unique:penduduks,nik',
                    'nama' => 'required|string|max:255',
                    'jenis_kelamin' => 'required|string|in:L,P',
                    'tempat_lahir' => 'required|string|max:255',
                    'tgl_lahir' => 'required|date',
                    'pekerjaan' => 'required|string|max:255',
                    'hubungan_keluarga' => 'required|string|max:255',
                    'tamatan' => 'required|string|max:255',
                    'dusun' => 'nullable|string|max:255',
                    // Modified validation: Allow either existing KK ID or new KK number
                    'kartu_keluarga_id' => 'required|string',
                    'new_kk_number' => 'nullable|string|size:16|unique:kartu_keluarga,no_kk',
                ]);
            } else {
                // Family arrival validation
                $rules = array_merge($rules, [
                    'family_nomor_kk' => 'required|string|unique:kartu_keluarga,no_kk',
                    'family_kategori_sejahtera' => 'nullable|string|max:50',
                    'family_jenis_bangunan' => 'nullable|string|max:100',
                    'family_pemakaian_air' => 'nullable|string|max:100',
                    'family_jenis_bantuan' => 'nullable|string|max:100',
                    'family_anggota' => 'required|array|min:1',
                    'family_anggota.*.nik' => 'required|string|size:16|unique:penduduks,nik',
                    'family_anggota.*.nama' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-]+$/',
                    'family_anggota.*.jenis_kelamin' => 'required|string|in:L,P',
                    'family_anggota.*.tempat_lahir' => 'required|string|max:100',
                    'family_anggota.*.tgl_lahir' => 'required|date|before:today',
                    'family_anggota.*.pekerjaan' => 'required|string|max:100',
                    'family_anggota.*.hubungan_keluarga' => 'required|string|max:50',
                    'family_anggota.*.tamatan' => 'required|string|max:100',
                ]);
            }
        }

        // Validasi khusus untuk MENINGGAL atau PINDAH (perlu penduduk yang ada)
        if (in_array($request->jenis_mutasi, ['MENINGGAL', 'PINDAH'])) {
            $rules['penduduk_id'] = 'required|exists:penduduks,id';
        }

        $request->validate($rules);

        // Custom validation for KK number handling
        if (in_array($request->jenis_mutasi, ['LAHIR', 'DATANG']) &&
            ($request->jenis_mutasi === 'LAHIR' || $request->arrival_mode === 'individu')) {

            $kkValue = $request->kartu_keluarga_id;
            $newKkNumber = $request->new_kk_number;

            // Check if this is a new KK number (starts with 'new_')
            if (str_starts_with($kkValue, 'new_')) {
                $newKkNumber = substr($kkValue, 4); // Remove 'new_' prefix
                $request->merge(['new_kk_number' => $newKkNumber]);
            }

            // Validate the KK logic
            if ($newKkNumber) {
                // This is a new KK number, validate it doesn't exist
                if (KartuKeluarga::where('no_kk', $newKkNumber)->exists()) {
                    return redirect()->back()
                        ->withErrors(['kartu_keluarga_id' => 'Nomor KK ini sudah ada di sistem. Silakan pilih dari daftar.'])
                        ->withInput();
                }
            } else {
                // This should be an existing KK ID
                if (!is_numeric($kkValue) || !KartuKeluarga::where('id', $kkValue)->exists()) {
                    return redirect()->back()
                        ->withErrors(['kartu_keluarga_id' => 'Kartu Keluarga yang dipilih tidak valid.'])
                        ->withInput();
                }
            }
        }

        // Gunakan DB Transaction untuk memastikan konsistensi data
        return DB::transaction(function () use ($request) {
            try {
                $pendudukId = null;

                // KASUS A: LAHIR atau DATANG - Buat penduduk baru
                if (in_array($request->jenis_mutasi, ['LAHIR', 'DATANG'])) {
                    $pendudukIds = [];

                    if ($request->jenis_mutasi === 'LAHIR' || $request->arrival_mode === 'individu') {
                        // Individual arrival or birth
                        $nik = $request->nik;
                        if ($request->jenis_mutasi === 'LAHIR' && empty($nik)) {
                            $nik = $this->generateNik($request->tgl_lahir, $request->jenis_kelamin);
                        }

                        // Handle both existing and new KK
                        $kartuKeluargaId = $request->kartu_keluarga_id;
                        $newKkNumber = $request->new_kk_number;

                        // Check if we need to create a new KartuKeluarga
                        if ($newKkNumber || str_starts_with($kartuKeluargaId, 'new_')) {
                            // Create new KartuKeluarga for the individual
                            $newKartuKeluarga = KartuKeluarga::create([
                                'no_kk' => $newKkNumber ?: substr($kartuKeluargaId, 4),
                                'dusun' => $request->dusun, // Save the dusun field from form
                                'kategori_sejahtera' => null, // Can be filled later
                                'jenis_bangunan' => null, // Can be filled later
                                'pemakaian_air' => null, // Can be filled later
                                'jenis_bantuan' => null, // Can be filled later
                            ]);
                            $kartuKeluargaId = $newKartuKeluarga->id;
                        } elseif (is_numeric($kartuKeluargaId)) {
                            // For existing KK, update the dusun field if provided
                            $existingKK = KartuKeluarga::find($kartuKeluargaId);
                            if ($existingKK && $request->dusun && $existingKK->dusun !== $request->dusun) {
                                $existingKK->update(['dusun' => $request->dusun]);
                            }
                        }

                        $penduduk = Penduduk::create([
                            'nik' => $nik,
                            'nama' => $request->nama,
                            'jenis_kelamin' => $request->jenis_kelamin,
                            'tempat_lahir' => $request->tempat_lahir,
                            'tgl_lahir' => $request->tgl_lahir,
                            'pekerjaan' => $request->pekerjaan,
                            'hubungan_keluarga' => $request->hubungan_keluarga,
                            'tamatan' => $request->tamatan,
                            'dusun' => $request->dusun,
                            'kartu_keluarga_id' => $kartuKeluargaId,
                            'status' => 'HIDUP',
                        ]);

                        $pendudukIds[] = $penduduk->id;
                    } else {
                        // Family arrival - Create new Kartu Keluarga first
                        $kartuKeluarga = KartuKeluarga::create([
                            'no_kk' => $request->family_nomor_kk,
                            'dusun' => $request->dusun, // Also save dusun for family arrival
                            'kategori_sejahtera' => $request->family_kategori_sejahtera,
                            'jenis_bangunan' => $request->family_jenis_bangunan,
                            'pemakaian_air' => $request->family_pemakaian_air,
                            'jenis_bantuan' => $request->family_jenis_bantuan,
                        ]);

                        // Create all family members
                        foreach ($request->family_anggota as $index => $anggota) {
                            // Calculate age if not provided
                            if (!isset($anggota['usia']) || empty($anggota['usia'])) {
                                $anggota['usia'] = \Carbon\Carbon::parse($anggota['tgl_lahir'])->age;
                            }

                            $penduduk = Penduduk::create([
                                'nik' => $anggota['nik'],
                                'nama' => ucwords(strtolower($anggota['nama'])),
                                'jenis_kelamin' => $anggota['jenis_kelamin'],
                                'tempat_lahir' => ucwords(strtolower($anggota['tempat_lahir'])),
                                'tgl_lahir' => $anggota['tgl_lahir'],
                                'usia' => $anggota['usia'],
                                'pekerjaan' => ucwords(strtolower($anggota['pekerjaan'])),
                                'hubungan_keluarga' => $anggota['hubungan_keluarga'],
                                'tamatan' => $anggota['tamatan'],
                                'kartu_keluarga_id' => $kartuKeluarga->id,
                                'status' => 'HIDUP',
                            ]);

                            $pendudukIds[] = $penduduk->id;
                        }
                    }

                    // For DATANG mutations, create one mutation record per person
                    // For LAHIR, create one mutation record for the birth
                    if ($request->jenis_mutasi === 'DATANG') {
                        foreach ($pendudukIds as $pendudukId) {
                            Mutasi::create([
                                'penduduk_id' => $pendudukId,
                                'jenis_mutasi' => $request->jenis_mutasi,
                                'tanggal_kejadian' => $request->tanggal_kejadian,
                                'lokasi_detail' => $request->lokasi_detail,
                                'keterangan' => $request->keterangan,
                            ]);
                        }
                        // Set pendudukId to first person for success message
                        $pendudukId = $pendudukIds[0];
                    } else {
                        // For LAHIR, use the first (and only) penduduk ID
                        $pendudukId = $pendudukIds[0];
                    }
                }
                // KASUS B: MENINGGAL atau PINDAH - Update status penduduk yang ada
                elseif (in_array($request->jenis_mutasi, ['MENINGGAL', 'PINDAH'])) {
                    $penduduk = Penduduk::findOrFail($request->penduduk_id);

                    if ($request->jenis_mutasi === 'MENINGGAL') {
                        // KASUS KHUSUS: MENINGGAL - Gunakan soft delete
                        // Ini akan menyembunyikan penduduk dari query normal tapi tetap mempertahankan data
                        // untuk integritas log mutasi dan keperluan audit trail
                        $penduduk->status = 'MENINGGAL'; // Update status terlebih dahulu
                        $penduduk->save();
                        $penduduk->delete(); // Soft delete: set deleted_at timestamp

                    } elseif ($request->jenis_mutasi === 'PINDAH') {
                        // KASUS PINDAH: Gunakan soft delete seperti MENINGGAL
                        // Ini akan menyembunyikan penduduk dari query normal population table
                        // tapi tetap mempertahankan data untuk log mutasi dan keperluan audit trail
                        $penduduk->status = 'PINDAH'; // Update status terlebih dahulu
                        $penduduk->save();
                        $penduduk->delete(); // Soft delete: set deleted_at timestamp
                    }

                    $pendudukId = $penduduk->id;
                }

                // KASUS C: Selalu simpan log mutasi (hanya jika belum dibuat di atas)
                if (!in_array($request->jenis_mutasi, ['DATANG']) || !isset($pendudukIds)) {
                    // For LAHIR and other mutations (DATANG individual sudah dihandle di atas)
                    Mutasi::create([
                        'penduduk_id' => $pendudukId,
                        'jenis_mutasi' => $request->jenis_mutasi,
                        'tanggal_kejadian' => $request->tanggal_kejadian,
                        'lokasi_detail' => $request->lokasi_detail,
                        'keterangan' => $request->keterangan,
                    ]);
                }

                // Create appropriate success message
                $successMessage = 'Data mutasi berhasil disimpan.';
                if ($request->jenis_mutasi === 'DATANG' && isset($pendudukIds)) {
                    $memberCount = count($pendudukIds);
                    if ($request->arrival_mode === 'keluarga') {
                        $successMessage = "✅ Data kedatangan berhasil disimpan! {$memberCount} anggota keluarga dengan Nomor KK {$request->family_nomor_kk} telah ditambahkan.";
                    } else {
                        // Check if this was a new KK or existing KK
                        if ($request->new_kk_number || str_starts_with($request->kartu_keluarga_id, 'new_')) {
                            $kkNumber = $request->new_kk_number ?: substr($request->kartu_keluarga_id, 4);
                            $successMessage = "✅ Data kedatangan berhasil disimpan! Penduduk baru telah ditambahkan dengan Nomor KK {$kkNumber}.";
                        } else {
                            $successMessage = "✅ Data kedatangan individu berhasil disimpan pada KK yang ada.";
                        }
                    }
                } elseif ($request->jenis_mutasi === 'LAHIR') {
                    $successMessage = "✅ Data kelahiran berhasil dicatat.";
                }

                // Flash session success message that can be detected by Data Penduduk page
                session()->flash('data_updated', true);

                return redirect()->route('mutasi.index')
                    ->with('success', $successMessage);

            } catch (\Exception $e) {
                // Transaction akan otomatis rollback jika terjadi error
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        });
    }

    /**
     * Helper method untuk generate NIK otomatis (simplifikasi)
     * Catatan: Dalam implementasi nyata, NIK generation lebih kompleks
     *
     * Format NIK Indonesia: PPLKKPPDDMMYYXXXX
     * - PP: Kode provinsi (2 digit)
     * - L: Kode kabupaten/kota (2 digit)
     * - K: Kode kecamatan (3 digit)
     * - PPDDMMYY: Tanggal lahir (6 digit) - untuk wanita ditambah 40
     * - XXXX: Nomor urut (4 digit)
     */
    private function generateNik($tglLahir, $jenisKelamin)
    {
        // Untuk implementasi sederhana, gunakan format: tanggal + random 8 digit
        // Dalam implementasi nyata, perlu mengikuti format NIK Indonesia

        $datePart = date('dmy', strtotime($tglLahir));

        // Untuk perempuan, tanggal lahir ditambah 40 (standar NIK Indonesia)
        if ($jenisKelamin === 'P') {
            $tanggal = (int)date('d', strtotime($tglLahir));
            $tanggal += 40;
            $datePart = $tanggal . date('my', strtotime($tglLahir));
        }

        $randomPart = Str::padLeft(mt_rand(1, 9999), 4, '0');

        return $datePart . $randomPart;
    }

    /**
     * Display the specified resource.
     */
    public function show(Mutasi $mutasi): View
    {
        // Load mutasi dengan relasi penduduk dan kartu keluarga
        $mutasi->load('penduduk.kartuKeluarga');

        return view('mutasi.show', compact('mutasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mutasi $mutasi): View
    {
        // Get all penduduk untuk dropdown
        $penduduk = Penduduk::with('kartuKeluarga')
            ->orderBy('nama')
            ->get();

        // Load mutasi dengan relasi penduduk
        $mutasi->load('penduduk');

        return view('mutasi.edit', compact('mutasi', 'penduduk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mutasi $mutasi)
    {
        // VALIDASI
        $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id',
            'jenis_mutasi' => 'required|in:LAHIR,MENINGGAL,DATANG,PINDAH',
            'tanggal_kejadian' => 'required|date',
            'lokasi_detail' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            // Validasi NIK jika diisi
            'nik' => 'nullable|numeric|digits:16|unique:penduduks,nik,' . $request->penduduk_id,
        ]);

        try {
            DB::beginTransaction();

            // UPDATE DATA MUTASI
            $mutasi->update([
                'penduduk_id' => $request->penduduk_id,
                'jenis_mutasi' => $request->jenis_mutasi,
                'tanggal_kejadian' => $request->tanggal_kejadian,
                'lokasi_detail' => $request->lokasi_detail,
                'keterangan' => $request->keterangan,
            ]);

            // UPDATE NIK PENDUDUK JIKA ADA PERUBAHAN
            if ($request->has('nik') && !empty($request->nik)) {
                $penduduk = Penduduk::findOrFail($request->penduduk_id);
                if ($penduduk->nik !== $request->nik) {
                    $penduduk->update(['nik' => $request->nik]);
                    \Log::info("NIK updated via Mutasi Edit for Penduduk ID {$penduduk->id}");
                }
            }

            DB::commit();

            return redirect()->route('mutasi.index')
                ->with('success', 'Data mutasi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mutasi $mutasi)
    {
        try {
            // Hapus data mutasi
            $mutasi->delete();

            return redirect()->route('mutasi.index')
                ->with('success', 'Data mutasi berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
