@extends('layout.template')

@section('breadcrumbs')
    Update Pengajuan Lembur
@endsection

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Update Lembur Kolektif</h3>

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

        <button class="btn btn-success float-right">Import .xlsx</button>
    </div>
    <div class="card-body fluid">
        <form action="{{ route('overtime.update', $pengajuan->id) }}" method="POST">
            @csrf
            @method('P  UT')
             <!-- Input fields for current_status, current_approver, and pengajuan_status -->
            <input type="hidden" name="current_status" value="pending">
            <input type="hidden" name="current_approver" value="{{ Auth::user()->npk }}">
            <input type="hidden" name="pengajuan_status" value="pending">
            <table class="table table-bordered">
                <tr>
                    <td>
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $pengajuan->tanggal }}" required />
                    </td>
                    <td>
                        <label>Hari</label>
                        <input class="form-control" type="text" id="hari" placeholder="hari" readonly value="{{ \Carbon\Carbon::parse($pengajuan->tanggal)->isoFormat('dddd') }}">
                    </td>
                    <td>
                        <label>Departemen</label>
                        <select class="form-control select2bs4" name="department_id" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $pengajuan->department_id == $department->id ? 'selected' : '' }}>{{ $department->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>

            <table class="table table-bordered mt-4" id="table">
                <thead>
                    <tr>
                        <th>NPK</th>
                        <th>Jadwal Shift Kerja</th>
                        <th>Pekerjaan yang Dilakukan</th>
                        <th>Rencana Jam Mulai Lembur</th>
                        <th>Rencana Jam Selesai Lembur</th>
                        <th>Total Rencana Waktu</th>
                        <th>Keterangan/Alasan</th>
                        <th>Tul</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuan->detailPengajuan as $key => $detail)
                        <tr>
                            <td>
                                <select class="form-control select2bs4" name="npk[]">
                                    @foreach($karyawanNPK as $data)
                                        <option value="{{ $data->npk }}" {{ $detail->npk == $data->npk ? 'selected' : '' }}>
                                            {{ $data->npk . ' - ' . $data->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="jadwal_kerja[]" class="form-control select2bs4">
                                    @foreach($jadwalKerja as $jd)
                                        <option value="{{ $jd }}" {{ $detail->jadwal_kerja == $jd ? 'selected' : '' }}>
                                            {{ ucfirst($jd) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <textarea name="pekerjaan_yang_dilakukan[]" placeholder="pekerjaan yang dilakukan" class="form-control" cols="10" rows="0">{{ $detail->pekerjaan_yang_dilakukan }}</textarea>
                            </td>
                            <td>
                                <input type="time" name="waktu_mulai[]" class="form-control waktu_mulai" value="{{ $detail->planOvertime->waktu_mulai }}">
                            </td>
                            <td>
                                <input type="time" name="waktu_selesai[]" class="form-control waktu_selesai" value="{{ $detail->planOvertime->waktu_selesai }}">
                            </td>
                            <td>
                                <input type="number" name="total_waktu[]" class="form-control total_waktu" readonly value="{{ $detail->planOvertime->total_waktu }}">
                            </td>
                            <td>
                                <textarea name="keterangan[]" placeholder="keterangan" class="form-control">{{ $detail->keterangan }}</textarea>
                            </td>
                            <td>
                                <input type="text" name="tul[]" class="form-control" value="{{ $detail->tul }}">
                            </td>
                            <td>
                                <button class="btn btn-primary remove-table-row" type="button">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="card-footer">
                <button type="submit" class="btn btn-success float-right">Update</button>
                <a href="/overtime" class="btn btn-outline-primary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        document.getElementById('tanggal').addEventListener('change', function () {
            var tanggal = new Date(this.value);
            var hari = tanggal.toLocaleDateString('id-ID', { weekday: 'long' });
            document.getElementById('hari').value = hari;
        });

        $(document).on('change', '.waktu_mulai, .waktu_selesai', function () {
            const row = $(this).closest('tr');
            const waktuMulai = row.find('.waktu_mulai').val();
            const waktuSelesai = row.find('.waktu_selesai').val();

            if (waktuMulai && waktuSelesai) {
                const mulai = new Date(`1970-01-01T${waktuMulai}:00`);
                const selesai = new Date(`1970-01-01T${waktuSelesai}:00`);

                let totalWaktu = (selesai - mulai) / 1000 / 60 / 60;
                if (totalWaktu < 0) {
                    totalWaktu += 24;
                }

                row.find('.total_waktu').val(Math.round(totalWaktu));
            }
        });

        $(document).on('click', '.remove-table-row', function () {
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection
