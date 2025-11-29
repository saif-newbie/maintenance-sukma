<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\KartuKeluarga;
use App\Models\Mutasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class PendudukController extends Controller
{
    /**
     * Get available dusuns for filter
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Start with base query for KartuKeluarga
        $query = KartuKeluarga::query();

        // Filter by Dusun (if applicable to KK or through Penduduk)
        // Assuming 'dusun' is on Penduduk model, we filter KKs that have members in that dusun
        if ($request->filled('dusun')) {
            $query->whereHas('penduduk', function ($q) use ($request) {
                $q->where('dusun', $request->dusun);
            });
        }

        // Search by Name, NIK, or No KK
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_kk', 'like', "%{$search}%")
                  ->orWhereHas('penduduk', function ($subQ) use ($search) {
                      $subQ->where('nama', 'like', "%{$search}%")
                           ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        // Get the results ordered by creation date
        $kartuKeluarga = $query->orderBy('created_at', 'asc')->get();

        $groupedPenduduk = collect();

        foreach ($kartuKeluarga as $kk) {
            // Get members for this KK
            // Apply filters to members as well to ensure consistency
            $anggotaQuery = Penduduk::with('kartuKeluarga')
                ->where('kartu_keluarga_id', $kk->id)
                ->orderBy('created_at', 'asc');

            if ($request->filled('dusun')) {
                $anggotaQuery->where('dusun', $request->dusun);
            }
            
            // If searching by Name/NIK, we might want to highlight or only show matching members?
            // Usually in family grouping, we show the whole family if one matches, OR just the matching ones.
            // Requirement says "population table will begin displaying results".
            // Let's show all family members if the family matches the search criteria (KK match or Member match)
            // BUT if we want to be strict, we could filter members too.
            // However, the view structure relies on grouping by KK.
            // If we filter members strictly, a KK might show up with 0 members if only the KK number matched?
            // Let's stick to showing all members of the matching families for context, 
            // OR we can filter members if the search is specific to a person.
            
            // Let's keep it simple: Show all members of the matching families.
            // This preserves the "Family" context which seems important.
            
            $anggota = $anggotaQuery->get();

            if ($anggota->isNotEmpty()) {
                $groupedPenduduk->put($kk->no_kk, $anggota);
            }
        }

        // Calculate running number for display
        $no = 1;

        // Get available dusuns for filter
        $availableDusuns = KartuKeluarga::distinct()
            ->whereNotNull('dusun')
            ->where('dusun', '!=', '')
            ->orderBy('dusun')
            ->pluck('dusun');

        // Return partial view for AJAX requests
        if ($request->ajax()) {
            return view('partials.penduduk_table', compact('groupedPenduduk', 'no'))->render();
        }

        return view('Keluarga.index', compact('groupedPenduduk', 'no', 'availableDusuns'));
    }

    /**
     * Helper method untuk mencatat mutasi penduduk
     */
    private function catatMutasi($pendudukId, $jenisMutasi, $keterangan = null, $lokasiDetail = null)
    {
        try {
            Mutasi::create([
                'penduduk_id' => $pendudukId,
                'jenis_mutasi' => $jenisMutasi,
                'tanggal_kejadian' => Carbon::now()->format('Y-m-d'),
                'lokasi_detail' => $lokasiDetail,
                'keterangan' => $keterangan ?? "Mutasi otomatis tercatat saat {$jenisMutasi}",
            ]);
        } catch (\Exception $e) {
            // Log error tapi tidak hentikan proses utama
            \Log::error("Gagal mencatat mutasi: " . $e->getMessage());
        }
    }

    /**
     * Helper method untuk menentukan dan mencatat mutasi penduduk baru
     */
    private function catatMutasiPendudukBaru($penduduk)
    {
        $usia = $penduduk->usia ?? $this->hitungUsia($penduduk->tgl_lahir);
        $hubungan = strtolower($penduduk->hubungan_keluarga);

        // Logika penentuan jenis mutasi
        if ($usia <= 1 && ($hubungan == 'anak' || $hubungan == 'child')) {
            // Jika usia <= 1 tahun dan hubungan anak, dianggap LAHIR
            $jenisMutasi = 'LAHIR';
            $keterangan = "Penduduk baru (kelahiran) - {$penduduk->nama}, usia {$usia} tahun";
        } else {
            // Selain itu dianggap DATANG (pendatang)
            $jenisMutasi = 'DATANG';
            $keterangan = "Penduduk baru (pendatang) - {$penduduk->nama}, {$penduduk->hubungan_keluarga}";
        }

        $this->catatMutasi($penduduk->id, $jenisMutasi, $keterangan, null);
    }

    /**
     * Helper method untuk menghitung usia berdasarkan tanggal lahir
     */
    private function hitungUsia($tanggalLahir)
    {
        return Carbon::parse($tanggalLahir)->age;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Keluarga.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        // VALIDASI DATA FORM - Clean validation with proper error messages
        $validator = \Validator::make($request->all(), [
            // Data Kartu Keluarga
            'nomor_kk' => [
                'required',
                'numeric',
                'digits:16',
                'unique:kartu_keluarga,no_kk'
            ],
            'dusun' => 'required|string|max:50',
            'kategori_sejahtera' => 'nullable|string|max:50',
            'jenis_bangunan' => 'nullable|string|max:100',
            'pemakaian_air' => 'nullable|string|max:100',
            'jenis_bantuan' => 'nullable|string|max:100',

            // Data Anggota Keluarga
            'anggota' => 'required|array|min:1',
            'anggota.*.nik' => [
                'required',
                'numeric',
                'digits:16',
                'unique:penduduks,nik'
            ],
            'anggota.*.nama' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-]+$/',
            'anggota.*.jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan,L,P,Male,Female',
            'anggota.*.tempat_lahir' => 'required|string|max:100',
            'anggota.*.tgl_lahir' => 'required|date|before:today',
            'anggota.*.hubungan_keluarga' => 'required|string|max:50',
            'anggota.*.tamatan' => 'required|string|max:100',
            'anggota.*.pekerjaan' => 'required|string|max:100',
        ], [
            // Custom validation messages
            'nomor_kk.required' => 'Nomor KK wajib diisi',
            'nomor_kk.numeric' => 'Nomor KK harus berupa angka',
            'nomor_kk.digits' => 'Nomor KK harus 16 digit',
            'nomor_kk.unique' => 'Nomor KK sudah terdaftar',

            'anggota.required' => 'Minimal harus ada 1 anggota keluarga',
            'anggota.min' => 'Minimal harus ada 1 anggota keluarga',

            'anggota.*.nik.required' => 'NIK anggota wajib diisi',
            'anggota.*.nik.numeric' => 'NIK harus berupa angka',
            'anggota.*.nik.digits' => 'NIK harus 16 digit',
            'anggota.*.nik.unique' => 'NIK sudah terdaftar',

            'anggota.*.nama.required' => 'Nama anggota wajib diisi',
            'anggota.*.nama.regex' => 'Nama hanya boleh mengandung huruf, spasi, titik, dan tanda hubung',

            'anggota.*.jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'anggota.*.jenis_kelamin.in' => 'Jenis kelamin hanya boleh: Laki-laki, Perempuan, L, P, Male, atau Female',

            'anggota.*.tempat_lahir.required' => 'Tempat lahir wajib diisi',

            'anggota.*.tgl_lahir.required' => 'Tanggal lahir wajib dipilih',
            'anggota.*.tgl_lahir.before' => 'Tanggal lahir tidak boleh hari ini atau masa depan',

            'anggota.*.hubungan_keluarga.required' => 'Hubungan keluarga wajib dipilih',
            'anggota.*.tamatan.required' => 'Pendidikan terakhir wajib dipilih',
            'anggota.*.pekerjaan.required' => 'Pekerjaan wajib diisi',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali data yang dimasukkan.');
        }

        try {
            // Use database transaction for data integrity
            DB::beginTransaction();

            // Step 1: Create Kartu Keluarga record
            $kartuKeluargaData = [
                'no_kk' => $request->nomor_kk,
                'dusun' => $request->dusun,
                'kategori_sejahtera' => $request->kategori_sejahtera,
                'jenis_bangunan' => $request->jenis_bangunan,
                'pemakaian_air' => $request->pemakaian_air,
                'jenis_bantuan' => $request->jenis_bantuan,
            ];

            $kartuKeluarga = KartuKeluarga::create($kartuKeluargaData);

            if (!$kartuKeluarga) {
                throw new \Exception('Gagal membuat data Kartu Keluarga');
            }

            // Step 2: Create Penduduk records for each family member
            $savedMembers = [];
            $totalMembers = count($request->anggota);

            foreach ($request->anggota as $memberData) {
                // Calculate age if not provided
                if (!isset($memberData['usia']) || empty($memberData['usia'])) {
                    $memberData['usia'] = Carbon::parse($memberData['tgl_lahir'])->age;
                }

                // Standardize gender format to single character for database compatibility
                // Handle multiple input formats: Laki-laki, Perempuan, L, P, Male, Female
                $genderInput = strtolower(trim($memberData['jenis_kelamin']));
                if (in_array($genderInput, ['l', 'laki-laki', 'male'])) {
                    $memberData['jenis_kelamin'] = 'L';
                } elseif (in_array($genderInput, ['p', 'perempuan', 'female'])) {
                    $memberData['jenis_kelamin'] = 'P';
                } else {
                    // Default fallback - if gender is not recognized, default to 'L'
                    $memberData['jenis_kelamin'] = 'L';
                    \Log::warning("Unrecognized gender input: {$memberData['jenis_kelamin']}, defaulting to 'L'");
                }

                $pendudukData = [
                    'kartu_keluarga_id' => $kartuKeluarga->id,
                    'nik' => $memberData['nik'],
                    'nama' => ucwords(strtolower($memberData['nama'])),
                    'jenis_kelamin' => $memberData['jenis_kelamin'],
                    'tempat_lahir' => ucwords(strtolower($memberData['tempat_lahir'])),
                    'tgl_lahir' => $memberData['tgl_lahir'],
                    'usia' => $memberData['usia'],
                    'pekerjaan' => ucwords(strtolower($memberData['pekerjaan'])),
                    'hubungan_keluarga' => $memberData['hubungan_keluarga'],
                    'tamatan' => $memberData['tamatan'],
                    'tamatan' => $memberData['tamatan'],
                    'dusun' => $request->dusun, // Sync dusun from KK to Penduduk
                    'status' => 'HIDUP', // Default status for new residents
                ];

                $penduduk = Penduduk::create($pendudukData);

                if (!$penduduk) {
                    throw new \Exception('Gagal menyimpan data anggota keluarga');
                }

                $savedMembers[] = $penduduk;

                // NOTE: Mutations should only be created through Mutation page
                // Not automatically when adding new residents
            }

            // Commit all changes
            DB::commit();

            // Success response
            return redirect()->route('penduduk.index')
                ->with('success', "✅ Data berhasil disimpan! {$totalMembers} anggota keluarga dengan Nomor KK {$request->nomor_kk} telah ditambahkan.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi data gagal: ' . $e->getMessage());

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Log database errors
            \Log::error('Database Error in PendudukController@store:', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            $errorMessage = 'Terjadi kesalahan database. ';

            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                if (str_contains($e->getMessage(), 'nik')) {
                    $errorMessage = 'NIK sudah terdaftar dalam sistem. Gunakan NIK yang berbeda.';
                } elseif (str_contains($e->getMessage(), 'no_kk')) {
                    $errorMessage = 'Nomor KK sudah terdaftar dalam sistem. Gunakan Nomor KK yang berbeda.';
                }
            } else {
                $errorMessage .= 'Hubungi administrator.';
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log general errors
            \Log::error('General Error in PendudukController@store:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Cari data penduduk dengan relasi kartu keluarga
        $penduduk = Penduduk::with('kartuKeluarga')->findOrFail($id);

        return view('show', compact('penduduk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) : View
    {
       $penduduk = Penduduk::findOrFail($id);

        return view('edit', compact('penduduk')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // VALIDASI
        $request->validate([
            'nik' => 'required|numeric|digits:16|unique:penduduks,nik,' . $id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'pekerjaan' => 'required|string|max:255',
            'hubungan_keluarga' => 'required|string|max:255',
            'tamatan' => 'required|string|max:255',
            'usia' => 'nullable|numeric|min:0|max:150',
        ]);

        try {
            DB::beginTransaction();

            // CARI DATA PENDUDUK
            $penduduk = Penduduk::findOrFail($id);

            // UPDATE DATA PENDUDUK
            $penduduk->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'pekerjaan' => $request->pekerjaan,
                'hubungan_keluarga' => $request->hubungan_keluarga,
                'tamatan' => $request->tamatan,
                'usia' => $request->usia,
            ]);

            DB::commit();

            return redirect()->route('penduduk.index')
                ->with('success', 'Data penduduk berhasil diperbarui.');

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
    public function destroy(string $id, Request $request)
    {
        try {
            // Cari data penduduk
            $penduduk = Penduduk::with('kartuKeluarga')->findOrFail($id);

            // Hapus data penduduk (permanent delete - forceDelete untuk bypass soft deletes)
            $penduduk->forceDelete();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('penduduk.index')
                ->with('success', "Data penduduk berhasil dihapus permanen dari sistem.");

        } catch (\Exception $e) {
            // Redirect kembali dengan pesan error
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete selected population data.
     */
    public function bulkDelete(Request $request)
    {
        try {
            $selectedIds = $request->input('selected_ids', []);

            if (empty($selectedIds)) {
                return redirect()->route('penduduk.index')
                    ->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
            }

            // Get the selected penduduk data for notification
            $selectedPenduduk = Penduduk::whereIn('id', $selectedIds)->get();
            $deletedCount = $selectedPenduduk->count();

            // Perform bulk delete (permanent delete - forceDelete untuk bypass soft deletes)
            Penduduk::whereIn('id', $selectedIds)->forceDelete();

            return redirect()->route('penduduk.index')
                ->with('success', "{$deletedCount} data penduduk berhasil dihapus permanen dari sistem.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified family resource.
     */
    public function familyShow(string $kartuKeluargaId): View
    {
        // Cari data kartu keluarga dengan semua anggotanya
        $kartuKeluarga = KartuKeluarga::with('penduduk')->findOrFail($kartuKeluargaId);

        return view('Keluarga.show', compact('kartuKeluarga'));
    }

    /**
     * Show the form for editing the specified family.
     */
    public function familyEdit(string $kartuKeluargaId): View
    {
        // Cari data kartu keluarga dengan semua anggotanya
        $kartuKeluarga = KartuKeluarga::with('penduduk')->findOrFail($kartuKeluargaId);

        return view('Keluarga.edit', compact('kartuKeluarga'));
    }

    /**
     * Update the specified family resource in storage.
     */
    public function familyUpdate(Request $request, string $kartuKeluargaId)
    {
        // VALIDASI dengan error handling yang lebih baik
        $validator = \Validator::make($request->all(), [
            // Parent (KK)
            'nomor_kk' => 'required|numeric|unique:kartu_keluarga,no_kk,' . $kartuKeluargaId,
            'kategori_sejahtera' => 'nullable|string|max:50',
            'jenis_bangunan' => 'nullable|string|max:100',
            'pemakaian_air' => 'nullable|string|max:100',
            'jenis_bantuan' => 'nullable|string|max:100',

            // Child (Penduduk)
            'anggota' => 'required|array|min:1',
            'anggota.*.nik' => 'required|numeric|digits:16',
            'anggota.*.nama' => 'required|string|max:255',
            'anggota.*.jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan,L,P,Male,Female',
            'anggota.*.tempat_lahir' => 'required|string|max:100',
            'anggota.*.tgl_lahir' => 'required|date|before:today',
            'anggota.*.hubungan_keluarga' => 'required|string|max:50',
            'anggota.*.tamatan' => 'required|string|max:100',
            'anggota.*.pekerjaan' => 'required|string|max:100',
        ], [
            // Custom validation messages
            'nomor_kk.required' => 'Nomor KK wajib diisi',
            'nomor_kk.numeric' => 'Nomor KK harus berupa angka',
            'nomor_kk.unique' => 'Nomor KK sudah terdaftar',

            'anggota.required' => 'Minimal harus ada 1 anggota keluarga',
            'anggota.min' => 'Minimal harus ada 1 anggota keluarga',

            'anggota.*.nik.required' => 'NIK anggota wajib diisi',
            'anggota.*.nik.numeric' => 'NIK harus berupa angka',
            'anggota.*.nik.digits' => 'NIK harus 16 digit',

            'anggota.*.nama.required' => 'Nama anggota wajib diisi',

            'anggota.*.jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'anggota.*.jenis_kelamin.in' => 'Jenis kelamin tidak valid',

            'anggota.*.tempat_lahir.required' => 'Tempat lahir wajib diisi',

            'anggota.*.tgl_lahir.required' => 'Tanggal lahir wajib dipilih',
            'anggota.*.tgl_lahir.before' => 'Tanggal lahir tidak boleh hari ini atau masa depan',

            'anggota.*.hubungan_keluarga.required' => 'Hubungan keluarga wajib dipilih',
            'anggota.*.tamatan.required' => 'Pendidikan terakhir wajib dipilih',
            'anggota.*.pekerjaan.required' => 'Pekerjaan wajib diisi',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa kembali data yang dimasukkan.');
        }

        try {
            DB::beginTransaction();

            // Log input data untuk debugging
            \Log::info('Family Update Request Data:', [
                'kartuKeluargaId' => $kartuKeluargaId,
                'request_data' => $request->all()
            ]);

            // UPDATE KK
            $kartuKeluarga = KartuKeluarga::findOrFail($kartuKeluargaId);

            $kartuKeluargaUpdateData = [
                'no_kk' => $request->nomor_kk,
                'kategori_sejahtera' => $request->kategori_sejahtera,
                'jenis_bangunan' => $request->jenis_bangunan,
                'pemakaian_air' => $request->pemakaian_air,
                'jenis_bantuan' => $request->jenis_bantuan,
            ];

            \Log::info('KK Update Data:', $kartuKeluargaUpdateData);

            $kartuKeluarga->update($kartuKeluargaUpdateData);

            // Collect existing IDs to track which ones to keep/delete
            $existingMemberIds = $kartuKeluarga->penduduk->pluck('id')->toArray();
            $processedMemberIds = [];
            $savedMembers = [];

            foreach ($request->anggota as $index => $orang) {
                // Calculate age from birth date
                $birthDate = new \Carbon\Carbon($orang['tgl_lahir']);
                $age = $birthDate->age;

                // Standardize gender format
                $genderInput = strtolower(trim($orang['jenis_kelamin']));
                if (in_array($genderInput, ['l', 'laki-laki', 'male'])) {
                    $standardizedGender = 'L';
                } elseif (in_array($genderInput, ['p', 'perempuan', 'female'])) {
                    $standardizedGender = 'P';
                } else {
                    $standardizedGender = 'L'; // Default fallback
                }

                $pendudukData = [
                    'kartu_keluarga_id' => $kartuKeluarga->id,
                    'nik' => $orang['nik'],
                    'nama' => ucwords(strtolower($orang['nama'])),
                    'jenis_kelamin' => $standardizedGender,
                    'tempat_lahir' => ucwords(strtolower($orang['tempat_lahir'])),
                    'tgl_lahir' => $orang['tgl_lahir'],
                    'usia' => $age,
                    'pekerjaan' => ucwords(strtolower($orang['pekerjaan'])),
                    'hubungan_keluarga' => $orang['hubungan_keluarga'],
                    'tamatan' => $orang['tamatan'],
                    'status' => 'HIDUP',
                ];

                // Check if this is an existing member (has ID)
                if (isset($orang['id']) && !empty($orang['id'])) {
                    $memberId = $orang['id'];
                    
                    // Verify this member belongs to this family (security check)
                    if (in_array($memberId, $existingMemberIds)) {
                        $penduduk = Penduduk::find($memberId);
                        
                        // Check NIK uniqueness excluding this member
                        $nikConflict = Penduduk::where('nik', $orang['nik'])
                            ->where('id', '!=', $memberId)
                            ->exists();
                            
                        if ($nikConflict) {
                            throw new \Exception("NIK {$orang['nik']} sudah digunakan oleh penduduk lain.");
                        }

                        $penduduk->update($pendudukData);
                        $processedMemberIds[] = $memberId;
                        $savedMembers[] = $penduduk;
                        \Log::info("Updated family member {$memberId}", $pendudukData);
                    } else {
                        // ID provided but not in this family? Treat as new or error?
                        // Let's treat as new but warn
                        \Log::warning("Member ID {$memberId} not found in family {$kartuKeluargaId}, creating new.");
                        
                        // Check NIK uniqueness
                        if (Penduduk::where('nik', $orang['nik'])->exists()) {
                             throw new \Exception("NIK {$orang['nik']} sudah digunakan oleh penduduk lain.");
                        }
                        
                        $penduduk = Penduduk::create($pendudukData);
                        $savedMembers[] = $penduduk;
                    }
                } else {
                    // New member
                    // Check NIK uniqueness
                    if (Penduduk::where('nik', $orang['nik'])->exists()) {
                         throw new \Exception("NIK {$orang['nik']} sudah digunakan oleh penduduk lain.");
                    }

                    $penduduk = Penduduk::create($pendudukData);
                    $savedMembers[] = $penduduk;
                    \Log::info("Created new family member", $pendudukData);
                }
            }

            // Delete members that were removed from the form
            $membersToDelete = array_diff($existingMemberIds, $processedMemberIds);
            if (!empty($membersToDelete)) {
                // Check if any of these have mutations?
                // If we delete them, mutations cascade delete.
                // If the user removed them from the form, they probably intend to delete them.
                // Or maybe move them? But this form is for editing the family.
                // Let's proceed with delete (forceDelete as per original logic)
                Penduduk::whereIn('id', $membersToDelete)->forceDelete();
                \Log::info("Deleted removed family members: " . implode(', ', $membersToDelete));
            }

            DB::commit();

            \Log::info('Family Update Success:', [
                'kk_id' => $kartuKeluarga->id,
                'members_saved' => count($savedMembers)
            ]);

            return redirect()->route('penduduk.index')
                ->with('success', '✅ Data keluarga berhasil diperbarui! ' . count($savedMembers) . ' anggota keluarga telah diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation Error in familyUpdate:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi data gagal: ' . $e->getMessage());

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            \Log::error('Database Error in familyUpdate:', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            $errorMessage = 'Terjadi kesalahan database. ';
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                if (str_contains($e->getMessage(), 'nik')) {
                    $errorMessage = 'NIK sudah terdaftar dalam sistem. Gunakan NIK yang berbeda.';
                } elseif (str_contains($e->getMessage(), 'no_kk')) {
                    $errorMessage = 'Nomor KK sudah terdaftar dalam sistem. Gunakan Nomor KK yang berbeda.';
                }
            } else {
                $errorMessage .= 'Hubungi administrator.';
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('General Error in familyUpdate:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified family from storage.
     */
    public function familyDestroy(string $kartuKeluargaId)
    {
        try {
            // Cari data kartu keluarga dengan semua anggotanya
            $kartuKeluarga = KartuKeluarga::with('penduduk')->findOrFail($kartuKeluargaId);

            // HAPUS PERMANEN SEMUA ANGGOTA KELUARGA (forceDelete untuk bypass soft deletes)
            foreach ($kartuKeluarga->penduduk as $penduduk) {
                // Catat mutasi sebelum hapus permanen
                $keteranganMutasi = "Seluruh keluarga (KK: {$kartuKeluarga->no_kk}) dihapus permanen dari sistem";
                $this->catatMutasi($penduduk->id, 'PINDAH', $keteranganMutasi);

                // Hapus permanen data penduduk
                $penduduk->forceDelete();
            }

            // Hapus permanen kartu keluarga
            $kartuKeluarga->forceDelete();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('penduduk.index')
                ->with('success', 'Data keluarga dan semua anggotanya berhasil dihapus permanen dari sistem.');

        } catch (\Exception $e) {
            // Redirect kembali dengan pesan error
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
