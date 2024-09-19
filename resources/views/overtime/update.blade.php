@extends('layout.template')

@section('content')
<div class="container">
    <h2>Edit Pengajuan Lembur</h2>
    <form action="{{ route('overtime.update', $pengajuan->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $pengajuan->tanggal }}" required>
        </div>

        <div class="form-group">
            <label for="department_id">Departemen</label>
            <select name="department_id" class="form-control" required>
                @foreach ($department as $dept)
                    <option value="{{ $dept->id }}" {{ $pengajuan->department_id == $dept->id ? 'selected' : '' }}>
                        {{ $dept->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>NPK</th>
                    <th>Jadwal Kerja</th>
                    <th>Pekerjaan</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Planning Waktu</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuan->detailPengajuan as $key => $detail)
                <tr>
                    <td>
                        <select name="npk[]" class="form-control">
                            @foreach ($karyawanNPK as $karyawan)
                                <option value="{{ $karyawan->npk }}" {{ $detail->npk == $karyawan->npk ? 'selected' : '' }}>
                                    {{ $karyawan->npk }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="jadwal_kerja[]" class="form-control">
                            @foreach ($jadwalKerja as $jadwal)
                                <option value="{{ $jadwal }}" {{ $detail->jadwal_kerja == $jadwal ? 'selected' : '' }}>
                                    {{ $jadwal }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="pekerjaan_yang_dilakukan[]" class="form-control" value="{{ $detail->pekerjaan_yang_dilakukan }}"></td>
                    <td><input type="time" name="waktu_mulai[]" class="form-control" value="{{ $detail->planOvertime->waktu_mulai }}"></td>
                    <td><input type="time" name="waktu_selesai[]" class="form-control" value="{{ $detail->planOvertime->waktu_selesai }}"></td>
                    <td><input type="int" name="planning_waktu[]" class="form-control" value="{{ $detail->planOvertime->planning_waktu }}"></td>
                    <td><input type="text" name="keterangan[]" class="form-control" value="{{ $detail->keterangan }}"></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Update Pengajuan</button>
    </form>
</div>
@endsection
