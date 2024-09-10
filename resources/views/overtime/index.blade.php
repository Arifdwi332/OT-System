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
                    <th>Id lembur</th>
                    <th>Departemen</th>
                    <th>Tanggal</th>
                    <th>NPK</th>
                    <th>Pekerjaan yang dilakukan</th>
                    <th>Rencana jam</th>
                    <th>Rencana jumlah jam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Id lembur</th>
                    <th>Departemen</th>
                    <th>Tanggal</th>
                    <th>NPK</th>
                    <th>Pekerjaan yang dilakukan</th>
                    <th>Rencana jam</th>
                    <th>Rencana jumlah jam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>   
            </tfoot>
        </table>
    </div>

    <div class="card-footer"></div>
</div>
@endsection