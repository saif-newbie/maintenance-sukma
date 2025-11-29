@foreach ($groupedPenduduk as $noKk => $anggotaKeluarga)
    @php
        $jumlahAnggota = $anggotaKeluarga->count();
        $isFirstRow = true;
        $kartuKeluargaId = $anggotaKeluarga->first()->kartu_keluarga_id;
    @endphp

    @foreach ($anggotaKeluarga as $penduduk)
        <tr>
            @if ($isFirstRow)
                <td rowspan="{{ $jumlahAnggota }}"
                    class="text-center align-middle valign-middle"
                    style="vertical-align: middle;">
                    {{ $no }}
                </td>
                <td rowspan="{{ $jumlahAnggota }}"
                    class="text-center align-middle valign-middle"
                    style="vertical-align: middle;">
                    <strong>{{ $penduduk->no_kk }}</strong>
                </td>
            @endif

            <td class="align-middle">
                <div>
                    {{ $penduduk->nama }}
                    @if ($penduduk->peran_keluarga == 'Kepala Keluarga')
                        <span class="badge badge-primary ml-2">KK</span>
                    @endif
                </div>
            </td>
            <td class="align-middle">{{ $penduduk->nik }}</td>
            <td class="align-middle">{{ $penduduk->peran_keluarga }}</td>
            <td class="align-middle">
                {{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
            </td>
            <td class="align-middle">{{ $penduduk->tempat_lahir }}</td>
            <td class="align-middle">
                {{ \Carbon\Carbon::parse($penduduk->tgl_lahir)->format('d/m/Y') }}</td>
            @if ($isFirstRow)
                <td rowspan="{{ $jumlahAnggota }}"
                    class="text-center align-middle valign-middle"
                    style="vertical-align: middle;">
                    <div class="btn-group-vertical" role="group">
                        <a href="{{ route('penduduk.family.show', $kartuKeluargaId) }}"
                            class="btn btn-sm btn-info mb-1" title="Detail Keluarga">
                            <i class="fas fa-users"></i> Detail
                        </a>
                        <a href="{{ route('penduduk.family.edit', $kartuKeluargaId) }}"
                            class="btn btn-sm btn-warning mb-1" title="Edit Keluarga">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger mb-1"
                            title="Hapus Keluarga"
                            onclick="hapusKeluarga({{ $kartuKeluargaId }})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </td>
            @endif
        </tr>
        @php
            $isFirstRow = false;
        @endphp
    @endforeach

    @php
        $no++;
    @endphp
@endforeach
