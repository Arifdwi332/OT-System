@extends('layout.template')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Data Overtime</h3>
        <a href="{{ route('overtime.planning') }}" class="btn btn-success float-right"><i class="fas fa-plus"></i> Add Planning</a>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Departemen</th>
                    <th>Hari</th>
                    <th>Tanggal</th>
                    <th>Jumlah Member</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuanData as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data['department'] }}</td>
                        <td>{{ $data['hari'] }}</td>
                        <td>{{ $data['tanggal'] }}</td>
                        <td>{{ $data['jumlah_member'] }}</td>
                        <td>
                            <a href="{{ route('overtime.show', $data['id']) }}" class="btn btn-info">Lihat Detail</a>
                            <a href="{{ route('overtime.edit', $data['id']) }}" class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
