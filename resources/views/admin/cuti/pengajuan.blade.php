@extends('layouts.admin')

@section('title', 'Pengajuan')

@section('content')

<h2>Pengajuan Cuti: {{ $karyawan->name }}</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Tanggal</th>
        <th>Jenis</th>
        <th>Durasi</th>
        <th>Status</th>
        <th>Keterangan</th>
        <th>Aksi</th>
    </tr>

```
@foreach($pengajuan as $p)
    <tr>
        <td>{{ $p->tanggal }}</td>
        <td>{{ $p->jenis }}</td>
        <td>{{ $p->durasi }} hari</td>
        <td>{{ ucfirst($p->status) }}</td>
        <td>{{ $p->keterangan }}</td>
        <td>
            @if($p->status == 'pending')
              <form action="{{ route('admin.pengajuan.acc', $p->id) }}" method="POST" style="display:inline-block;">
    @csrf
    <button type="submit" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">
        ACC
    </button>
</form>

<form action="{{ route('admin.pengajuan.tolak', $p->id) }}" method="POST" style="display:inline-block;">
    @csrf
    <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
        Tolak
    </button>
</form>

            @else
                <span class="text-gray-500">-</span>
            @endif
        </td>
    </tr>
@endforeach
```

</table>

@endsection
