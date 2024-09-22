@extends('layout.template')

@section('breadcrumbs')
    Detail Planning OT {{ $pengajuan->id }}
@endsection

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Detail Planning OT</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <h5>Informasi Pengajuan Planning OT</h5>
                <table id="example1" class="table table-bordered table-striped">
                    <tr>
                        <th>ID Pengajuan</th>
                        <td>{{ $pengajuan->id }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $pengajuan->tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Departemen</th>
                        <td>{{ $pengajuan->department->nama }}</td>
                    </tr>
                    <tr>
                        <th>Hari</th>
                        <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal)->translatedFormat('l') }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Member</th>
                        <td>{{ $pengajuan->detailPengajuan->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <h5>Detail Pengajuan</h5>
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>NPK</th>
                    <th>Shift Kerja</th>
                    <th>Pekerjaan Yang Dilakukan</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Total Waktu</th>
                    <th>Keterangan</th>
                    <th>Status Pengajuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->detailPengajuan as $detail)
                    @if($detail->planOvertime)
                    <tr>
                        <td>{{ $detail->npk }}</td>
                        <td>{{ $detail->jadwal_kerja }}</td>
                        <td>{{ $detail->pekerjaan_yang_dilakukan }}</td>
                        <td>{{ $detail->planOvertime->waktu_mulai }}</td>
                        <td>{{ $detail->planOvertime->waktu_selesai }}</td> 
                        <td>{{ $detail->planOvertime->planning_waktu }} jam</td>
                        <td>{{ $detail->keterangan}}</td>
                        <td>
                            @if($detail->planning_status == 'pending')
                                <span class="badge badge-secondary">Pending</span>
                            @elseif($detail->planning_status == 'approved by section head')
                                <span class="badge badge-primary">Approved by Section Head</span>
                            @elseif($detail->planning_status == 'approved by department head')
                                <span class="badge badge-success">Approved by Department Head</span>
                            @elseif($detail->planning_status == 'approved by division head')
                                <span class="badge badge-warning">Approved by Division Head</span>
                            @elseif($detail->planning_status == 'approved by hrd')
                                <span class="badge badge-info">Approved by HRD</span>
                            @elseif($detail->planning_status == 'rejected by section head')
                                <span class="badge badge-danger">Rejected by Section Head</span>
                            @elseif($detail->planning_status == 'rejected by department head')
                                <span class="badge badge-danger">Rejected by Department Head</span>
                            @elseif($detail->planning_status == 'rejected by division head')
                                <span class="badge badge-danger">Rejected by Division Head</span>
                            @elseif($detail->planning_status == 'rejected by hrd')
                                <span class="badge badge-danger">Rejected by HRD</span>
                            @endif
                        </td>
                    </tr>
                    @else
                        <tr>
                            <td colspan="8">Plan Overtime tidak ditemukan</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('overtime.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
