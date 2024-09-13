@extends('layout.template')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Lembur Kolektif</h3>
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
        <form action="{{ route('overtime.store') }}" method="POST">
            @csrf
            <table class="table table-bordered">
                <tr>
                    <td >
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control"/>
                    </td>
                    <td>
                        <label>Hari</label>
                        <input class="form-control" type="text" id="hari" placeholder="hari" readonly>
                    </td>
                    <td>
                        <label>Departemen</label>
                        <select class="form-control select2bs4" name="department_id">
                            @foreach($department as $department)
                                <option value="{{ $department->id }}">{{ $department->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    {{-- otomatis pending --}}
                    <input type="text" name="current_status" value="pending" hidden>
                    {{-- ambil dari npk yang login  --}}
                    <input type="number" name="current_approver" value="{{ Auth::user()->npk }}" hidden>
                    {{-- pada dashboard admin hanya ada pending --}}
                    <input type="text" name="pengajuan_status" value="pending" hidden>
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
                    <tr>
                        <td>
                            {{-- <input type="number" name="npk[]" class="form-control"> --}}
                            <Select class="form-control select2bs4" name="npk[]">
                                @foreach($karyawanNPK as $data)
                                    <option value="{{ $data->npk }}">{{ $data->npk . ' - ' . $data->nama }}</option>
                                @endforeach
                            </Select> 
                        </td>
                        <td>
                            <select name="jadwal_kerja[]" class="form-control select2bs4" >
                                @foreach($jadwalKerja as $jd)
                                    <option value="{{ $jd }}">{{ ucfirst($jd) }}</option>
                            @endforeach
                        </td>
                        <td>
                            <textarea name="pekerjaan_yang_dilakukan[]" placeholder="pekerjaan yang dilakukan" class="form-control" cols="10" rows="0"></textarea>
                        </td>
                        <td>
                            <input type="time" name="waktu_mulai[]" placeholder="mulai" class="form-control waktu_mulai">
                        </td>
                        <td>
                            <input type="time" name="waktu_selesai[]" placeholder="selesai" class="form-control waktu_selesai">
                        </td>
                        <td>
                            <input type="number" name="total_waktu[]" placeholder="total waktu" class="form-control total_waktu" readonly>
                        </td>
                        <td width="16%">
                            <textarea name="keterangan[]" placeholder="pekerjaan yang dilakukan" class="form-control" cols="10" rows="0"></textarea>
                        </td>
                        <td>
                            <input type="text" name="tul[]" placeholder="tul" class="form-control">
                            {{-- pada dashboard admin hanya ada pending --}}
                            <input type="text" name="dpstatus" value="pending" hidden>
                        </td>
                        <td>
                            <button class="btn btn-primary" type="button" id="add" name="add">Tambah</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="card-footer">
                <button type="submit" class="btn btn-success float-right">Ajukan</button>
                <a href="/overtime" class="btn btn-outline-primary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
    // Mengambil hari saat tanggal diubah
    document.getElementById('tanggal').addEventListener('change', function () {
        var tanggal = new Date(this.value);
        var hari = tanggal.toLocaleDateString('id-ID', { weekday: 'long' }); // Menampilkan hari dalam bahasa Indonesia
        document.getElementById('hari').value = hari;
    });

    var i = 0;
    $('#add').click(function () {
        ++i;
        $('#table tbody').append(`
            <tr>
                <td width="12%">
                    <Select class="form-control select2bs4" name="npk[]">
                        @foreach($karyawanNPK as $data)
                            <option value="{{ $data->npk }}">{{ $data->npk . ' - ' . $data->nama }}</option>
                        @endforeach
                    </Select>
                </td>
                <td width="8%">
                    <select name="jadwal_kerja[]" class="form-control select2bs4">
                        @foreach($jadwalKerja as $jd)
                            <option value="{{ $jd }}">{{ ucfirst($jd) }}</option>
                        @endforeach
                    </select>
                </td>
                <td width="16%">
                    <textarea name="pekerjaan_yang_dilakukan[]" placeholder="pekerjaan yang dilakukan" class="form-control" cols="10" rows="0"></textarea>
                </td>
                <td>
                    <input type="time" name="waktu_mulai[]" placeholder="mulai" class="form-control waktu_mulai">
                </td>
                <td>
                    <input type="time" name="waktu_selesai[]" placeholder="selesai" class="form-control waktu_selesai">
                </td>
                <td>
                    <input type="number" name="total_waktu[]" placeholder="total waktu" class="form-control total_waktu" readonly>
                </td>
                <td width="16%">
                    <textarea name="keterangan[]" placeholder="pekerjaan yang dilakukan" class="form-control" cols="10" rows="0"></textarea>
                </td>
                <td>
                    <input type="text" name="tul[]" placeholder="tul" class="form-control">
                    <input type="text" name="dpstatus" value="pending" hidden>
                </td>
                <td>
                    <button class="btn btn-primary remove-table-row" type="button">Hapus</button>
                </td>
            </tr>
        `);
    });

    // Event delegation untuk menghitung total waktu
    $(document).on('change', '.waktu_mulai, .waktu_selesai', function () {
        const row = $(this).closest('tr');
        const waktuMulai = row.find('.waktu_mulai').val();
        const waktuSelesai = row.find('.waktu_selesai').val();

        if (waktuMulai && waktuSelesai) {
            const mulai = new Date(`1970-01-01T${waktuMulai}:00`);
            const selesai = new Date(`1970-01-01T${waktuSelesai}:00`);

            let totalWaktu = (selesai - mulai) / 1000 / 60 / 60; // Hasil dalam jam

            // Jika waktu selesai lebih kecil dari waktu mulai (misalnya lembur melewati tengah malam)
            if (totalWaktu < 0) {
                totalWaktu += 24;
            }

            // Membulatkan ke bilangan bulat
            row.find('.total_waktu').val(Math.round(totalWaktu));
        }
    });

    // Hapus row
    $(document).on('click', '.remove-table-row', function () {
        $(this).closest('tr').remove();
    });
});
</script>
@endsection 
