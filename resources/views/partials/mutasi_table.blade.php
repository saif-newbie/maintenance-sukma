@php
    $nomor = 1;
@endphp

@foreach($mutasi as $item)
<tr class="@if($item->jenis_mutasi == 'LAHIR') table-info @elseif($item->jenis_mutasi == 'DATANG') table-success @elseif($item->jenis_mutasi == 'MENINGGAL') table-danger @elseif($item->jenis_mutasi == 'PINDAH') table-warning @endif">
    <td class="text-center align-middle">{{ $nomor++ }}</td>
    <td class="align-middle">
        <strong>{{ $item->penduduk->nama ?? 'Tidak Diketahui' }}</strong>
        @if($item->penduduk && $item->penduduk->hubungan_keluarga == 'Kepala Keluarga')
            <span class="badge badge-primary ml-2">KK</span>
        @endif
    </td>
    <td class="align-middle">{{ $item->penduduk->nik ?? '-' }}</td>
    <td class="align-middle">
        {{ $item->penduduk && $item->penduduk->kartuKeluarga ? $item->penduduk->kartuKeluarga->no_kk : '-' }}
    </td>
    <td class="align-middle">
        @switch($item->jenis_mutasi)
            @case('LAHIR')
                <span class="badge badge-success">👶 Lahir</span>
                @break
            @case('MENINGGAL')
                <span class="badge badge-dark">⚰️ Meninggal</span>
                @break
            @case('DATANG')
                <span class="badge badge-info">🏠 Datang</span>
                @break
            @case('PINDAH')
                <span class="badge badge-warning">🚚 Pindah</span>
                @break
            @default
                <span class="badge badge-secondary">{{ $item->jenis_mutasi }}</span>
        @endswitch
    </td>
    <td class="align-middle">{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d/m/Y') }}</td>
    <td class="align-middle">{{ $item->lokasi_detail ?: '-' }}</td>
    <td class="align-middle">
        <span title="{{ $item->keterangan }}">
            {{ \Illuminate\Support\Str::limit($item->keterangan ?: '-', 30) }}
        </span>
    </td>
    <td class="text-center align-middle">
        <div class="btn-group" role="group">
            <a href="{{ route('mutasi.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('mutasi.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('mutasi.destroy', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mutasi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@endforeach