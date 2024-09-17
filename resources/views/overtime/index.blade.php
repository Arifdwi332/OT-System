@extends('layout.template')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h3 class="card-title">Data Pengajuan Lembur</h3>
        <a href="/overtime/planning" class="btn btn-success float-right"><i class="fas fa-plus"></i> Add OT Planning</a>
    </div>

    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Pengajuan</th>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Department</th>
                    <th>Jumlah Member OT</th>
                    <th>Status Pengajuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuanData as $index => $pengajuan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pengajuan->id }}</td>
                        <td>{{ $pengajuan->tanggal }}</td>
                        <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal)->locale('id')->translatedFormat('l') }}</td>
                        <td>{{ $pengajuan->department->nama }}</td>
                        <td>{{ $pengajuan->detailPengajuan->count() }} Member</td>
                        <td>
                            @if($pengajuan->pengajuan_status == 'Approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($pengajuan->pengajuan_status == 'Pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($pengajuan->pengajuan_status == 'Rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-secondary">{{ $pengajuan->pengajuan_status }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('overtime.edit', $pengajuan->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button class="btn btn-warning"><i class="fas fa-eye"></i> Lihat</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer"></div>
</div>
@endsection