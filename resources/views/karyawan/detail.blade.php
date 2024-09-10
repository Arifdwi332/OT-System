@extends('layout.template')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Karyawan</h3>
    </div>

    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>NPK</th>
                    <th>Nama</th>
                    <th>Departemen</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $karyawan->npk }}</td>
                    <td>{{ $karyawan->nama }}</td>
                    <td>{{ $department->nama }}</td>
                    <td>{{ $karyawan->role }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <a href="/karyawan" class="btn btn-info float-right">Kembali</a>
    </div>
</div>
@endsection