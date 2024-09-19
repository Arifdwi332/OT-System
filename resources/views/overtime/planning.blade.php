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
        {{-- errors validation --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button class="btn btn-success float-right">Import .xlsx</button>
    </div>
    <div class="card-body fluid">
        <form action="{{ route('overtime.store') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 30%">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control"/>
                            @error('tanggal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </td>
                        <td style="width: 40%">
                            <label>Hari</label>
                            <input class="form-control" type="text" id="hari" placeholder="hari" readonly>
                        </td>
                        <td style="width: 30%">
                            <label>Departemen</label>
                            <select class="form-control select2bs4" name="department_id">
                                <option selected="selected">Select Department</option>
                                @foreach($department as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                        <tr>
                            <td>
                                <select class="form-control select2bs4" name="npk[]">
                                    <option selected="selected">Select NPK</option>
                                    @foreach($karyawanNPK as $data)
                                        <option value="{{ $data->npk }}">{{ $data->npk . ' - ' . $data->nama }}</option>
                                    @endforeach
                                </select>
                                @error('npk.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <select name="jadwal_kerja[]" class="form-control select2bs4">
                                    <option selected="selected">Select Shift</option>
                                    @foreach($jadwalKerja as $jd)
                                        <option value="{{ $jd }}">{{ ucfirst($jd) }}</option>
                                    @endforeach
                                </select>
                                @error('jadwal_kerja.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <textarea name="pekerjaan_yang_dilakukan[]" placeholder="pekerjaan yang dilakukan" class="form-control"></textarea>
                                @error('pekerjaan_yang_dilakukan.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="time" name="waktu_mulai[]" class="form-control waktu_mulai">
                                @error('waktu_mulai.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="time" name="waktu_selesai[]" class="form-control waktu_selesai">
                                @error('waktu_selesai.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="number" name="planning_waktu[]" class="form-control total_waktu" readonly>
                            </td>
                            <td>
                                <textarea name="keterangan[]" placeholder="keterangan" class="form-control"></textarea>
                                @error('keterangan.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <input type="text" name="dpstatus[]" value="pending" hidden>
                            </td>
                            <td>
                                <button class="btn btn-primary" id="add" type="button">Tambah</button>
                            </td>
                            <input type="text" name="planning_status[]" value="pending" hidden>
                            <input type="text" name="actual_status[]" value="pending" hidden>
                            <input type="text" name="reject_reason[]" value="test" hidden>
                            <input type="number" name="current_approver[]" value="{{ Auth::user()->npk }}" hidden>
                            <input type="text" name="dp_status[]" value="pending" hidden>
                        </tr>
                    </tbody>                
                </table>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success float-right">Ajukan</button>
            <a href="/overtime" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
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
                <td>
                    <select class="form-control select2bs4" name="npk[]">
                        <option selected="selected">Select NPK</option>
                        @foreach($karyawanNPK as $data)
                            <option value="{{ $data->npk }}">{{ $data->npk . ' - ' . $data->nama }}</option>
                        @endforeach
                    </select>
                    @error('npk.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
                <td>
                    <select name="jadwal_kerja[]" class="form-control select2bs4">
                        <option selected="selected">Select Shift</option>
                        @foreach($jadwalKerja as $jd)
                            <option value="{{ $jd }}">{{ ucfirst($jd) }}</option>
                        @endforeach
                    </select>
                    @error('jadwal_kerja.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
                <td>
                    <textarea name="pekerjaan_yang_dilakukan[]" placeholder="pekerjaan yang dilakukan" class="form-control"></textarea>
                    @error('pekerjaan_yang_dilakukan.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
                <td>
                    <input type="time" name="waktu_mulai[]" class="form-control waktu_mulai">
                    @error('waktu_mulai.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
                <td>
                    <input type="time" name="waktu_selesai[]" class="form-control waktu_selesai">
                    @error('waktu_selesai.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>
                <td>
                    <input type="number" name="planning_waktu[]" class="form-control total_waktu" readonly>
                </td>
                <td>
                    <textarea name="keterangan[]" placeholder="keterangan" class="form-control"></textarea>
                    @error('keterangan.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <input type="text" name="dpstatus[]" value="pending" hidden>
                </td>
                <td>
                    <button class="btn btn-danger remove-table-row" type="button" name="add">Hapus</button>
                </td>
                <input type="text" name="planning_status[]" value="pending" hidden>
                <input type="text" name="actual_status[]" value="pending" hidden>
                <input type="text" name="reject_reason[]" value="test" hidden>
                <input type="number" name="current_approver[]" value="{{ Auth::user()->npk }}" hidden>
                <input type="text" name="dp_status[]" value="pending" hidden>
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

        let totalWaktu = (selesai - mulai) / (1000 * 60 * 60); // Hasil dalam jam

        // Jika waktu selesai lebih kecil dari waktu mulai (misalnya lembur melewati tengah malam)
        if (totalWaktu < 0) {
            totalWaktu += 24;
        }

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
