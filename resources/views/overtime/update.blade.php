@extends('layout.template')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Edit Pengajuan Lembur</h3>
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
        <form action="{{ route('overtime.update', $pengajuan->id) }}" method="POST">
            @csrf
            @method('PUT')
            {{-- form --}}
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 30%">
                            <label>Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ $pengajuan->tanggal }}" required>
                        </td>
                        <td style="width: 40%">
                            <label>Hari</label>
                            <input class="form-control" type="text" id="hari" placeholder="hari" readonly>
                        </td>
                        <td style="width: 30%">
                            <label>Departemen</label>
                            <select class="form-control select2bs4" name="department_id" required>
                                @foreach($department as $dept)
                                    <option value="{{ $dept->id }}" {{ $pengajuan->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="table-responsive">
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
                                    <textarea name="pekerjaan_yang_dilakukan[]" class="form-control">{{ $detail->pekerjaan_yang_dilakukan }}</textarea>
                                </td>
                                <td>
                                    <input type="time" name="waktu_mulai[]" class="form-control waktu_mulai" value="{{ $detail->planOvertime->waktu_mulai }}">
                                </td>
                                <td>
                                    <input type="time" name="waktu_selesai[]" class="form-control waktu_selesai" value="{{ $detail->planOvertime->waktu_selesai }}">
                                </td>
                                <td>
                                    <input type="number" name="planning_waktu[]" class="form-control total_waktu" readonly value="{{ $detail->planOvertime->planning_waktu }}">
                                </td>
                                <td>
                                    <textarea name="keterangan[]" class="form-control">{{ $detail->keterangan }}</textarea>
                                </td>
                                <td>
                                    <button class="btn btn-danger remove-table-row" type="button">Hapus</button>
                                </td>
                                <input type="hidden" name="dpstatus[]" value="pending">
                                <input type="hidden" name="planning_status[]" value="pending">
                                <input type="hidden" name="actual_status[]" value="pending">
                                <input type="hidden" name="reject_reason[]" value="test">
                                <input type="hidden" name="current_approver[]" value="{{ Auth::user()->npk }}">
                                <input type="hidden" name="dp_status[]" value="pending">
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success float-right">Update Pengajuan</button>
            <a href="{{ route('overtime.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        // Setel hari saat halaman dimuat
        function updateHari() {
            var tanggal = $('#tanggal').val();
            if (tanggal) {
                var hari = new Date(tanggal).toLocaleDateString('id-ID', { weekday: 'long' });
                $('#hari').val(hari);
            }
        }

        // Panggil fungsi updateHari saat halaman dimuat
        updateHari();

        // Panggil fungsi updateHari saat tanggal diubah
        $('#tanggal').on('change', function () {
            updateHari();
        });

        // Event delegation untuk menghitung total waktu
        $(document).on('change', '.waktu_mulai, .waktu_selesai', function () {
            const row = $(this).closest('tr');
            const waktuMulai = row.find('.waktu_mulai').val();
            const waktuSelesai = row.find('.waktu_selesai').val();

            if (waktuMulai && waktuSelesai) {
                const mulai = new Date(`1970-01-01T${waktuMulai}:00`);
                const selesai = new Date(`1970-01-01T${waktuSelesai}:00`);

                let totalWaktu = (selesai - mulai) / (1000 * 60 * 60); // Hasil dalam jam

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
