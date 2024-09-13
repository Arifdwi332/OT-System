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
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Department</th>
                    <th>NPK</th>
                    <th>Nama</th>
                    <th>Pekerjaan yang Dilakukan</th>
                    <th>Shift</th>
                    <th>Rencana Mulai OT</th>
                    <th>Rencana Selesai OT</th>
                    <th>Toatal Jam Lembur</th>
                    <th>Status Pengajuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuanData as $index => $pengajuan)
                @foreach($pengajuan->detailPengajuan as $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pengajuan->tanggal }}</td>
                        <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal)->locale('id')->translatedFormat('l') }}</td>
                        <td>{{ $pengajuan->department->nama }}</td>
                        <td>{{ $detail->npk }}</td>
                        <td>{{ $detail->karyawan->nama }}</td>
                        <td>{{ $detail->pekerjaan_yang_dilakukan }}</td>
                        <td>{{ $detail->jadwal_kerja }}</td>
                        <td>{{ $detail->planOvertime->waktu_mulai }}</td>
                        <td>{{ $detail->planOvertime->waktu_selesai }}</td>
                        <td>{{ $detail->planOvertime->total_waktu }}</td>
                        <td>{{ $detail->dpstatus }}</td>
                        <td>
                            <button class="btn btn-warning"><i class="fas fa-eye"></i> </button>
                        </td>
                        {{-- <td>
                            <a href="{{ route('overtime.edit', $pengajuan->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('overtime.destroy', $pengajuan->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td> --}}
                    </tr>
                @endforeach
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Department</th>
                    <th>NPK</th>
                    <th>Nama</th>
                    <th>Pekerjaan yang Dilakukan</th>
                    <th>Shift</th>
                    <th>Rencana Mulai OT</th>
                    <th>Rencana Selesai OT</th>
                    <th>Toatal Jam Lembur</th>
                    <th>Status Pengajuan</th>
                    <th>Aksi</th>
                </tr> 
            </tfoot>
        </table>
    </div>

    <div class="card-footer"></div>
</div>
@endsection