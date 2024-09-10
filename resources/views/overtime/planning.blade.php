@extends('layout.template')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('overtime.store') }}" method="POST">
                @csrf
                <!-- Pengajuan Section -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pengajuan</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="department_id">Department</label>
                            <select name="department_id" class="form-control" required>
                                <!-- Populate with department data -->
                                @foreach($department as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Detail Pengajuan Section (Dynamic) -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pengajuan</h3>
                    </div>
                    <div class="card-body">
                        <table class="table" id="employee-table">
                            <thead>
                                <tr>
                                    <th>NPK</th>
                                    <th>Jadwal Kerja</th>
                                    <th>Pekerjaan Yang Dilakukan</th>
                                    <th>Keterangan</th>
                                    <th>TUL</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="employee-rows">
                                <tr>
                                    <td><input type="number" name="npk[]" class="form-control" required></td>
                                    <td>
                                        <select name="jadwal_kerja[]" class="form-control" required>
                                            <option value="shift 1">Shift 1</option>
                                            <option value="shift 2">Shift 2</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="pekerjaan_yang_dilakukan[]" class="form-control" required></td>
                                    <td><input type="text" name="keterangan[]" class="form-control"></td>
                                    <td><input type="number" name="tul[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" id="add-employee" class="btn btn-success">Tambah Karyawan</button>
                    </div>
                </div>

                <!-- Plan Overtime Section -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Plan Overtime</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="waktu_mulai">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="waktu_selesai">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="total_waktu">Total Waktu (Jam)</label>
                            <input type="number" name="total_waktu" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit Planning</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// Add new row for employee
$('#add-employee').click(function() {
    $('#employee-rows').append(`
        <tr>
            <td><input type="number" name="npk[]" class="form-control" required></td>
            <td>
                <select name="jadwal_kerja[]" class="form-control" required>
                    <option value="shift 1">Shift 1</option>
                    <option value="shift 2">Shift 2</option>
                </select>
            </td>
            <td><input type="text" name="pekerjaan_yang_dilakukan[]" class="form-control" required></td>
            <td><input type="text" name="keterangan[]" class="form-control"></td>
            <td><input type="number" name="tul[]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
        </tr>
    `);
});

// Remove row
$(document).on('click', '.remove-row', function() {
    $(this).closest('tr').remove();
});
</script>
@endsection

{{-- @section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Lembur Kolektif</h3>
        <button class="btn btn-success float-right">Upload .csv .xlsx</button>
    </div>
    <div class="card-body">
        <form action="{{ route('overtime.store') }}" method="POST"> <!-- Tambahkan route ke form action -->
            @csrf
            <table class="table table-bordered">
                <tr>
                    <td>
                        <label>Tanggal</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" name="tanggal" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <label>Hari</label>
                        <input class="form-control" type="text" name="hari" id="hari" placeholder="hari" readonly>
                    </td>
                    <td>
                        <label>Departemen</label>
                        <select class="form-control select2" style="width: 100%;" name="departemments_id">
                            @foreach($departements as $department)
                                <option value="{{ $department->id }}">{{ $department->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>

            <table class="table table-bordered mt-4" id="table">
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
                <tr>
                    <td><input type="text" name="npk[]" placeholder="npk" class="form-control"></td>
                    <td><input type="text" name="jadwal_kerja[]" placeholder="jadwal kerja" class="form-control"></td>
                    <td><input type="text" name="pekerjaan_yang_dilakukan[]" placeholder="pekerjaan yang dilakukan" class="form-control"></td>
                    <td><input type="text" name="waktu_mulai[]" placeholder="mulai" class="form-control"></td>
                    <td><input type="text" name="waktu_selesai[]" placeholder="selesai" class="form-control"></td>
                    <td><input type="text" name="total_waktu[]" placeholder="total waktu" class="form-control"></td>
                    <td><input type="text" name="keterangan[]" placeholder="keterangan" class="form-control"></td>
                    <td><input type="text" name="tul[]" placeholder="tul" class="form-control"></td>
                    <td>
                        <button class="btn btn-primary" type="button" id="add">Tambah</button>
                    </td>
                </tr>
            </table>

            <div class="card-footer">
                <button type="submit" class="btn btn-success float-right">Ajukan</button>
                <a href="/overtime" class="btn btn-outline-primary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var i = 0;
        $('#add').click(function (){
            ++i;
            $('#table').append(`
            <tr>
                <td><input type="text" name="npk[]" placeholder="npk" class="form-control"></td>
                <td><input type="text" name="jadwal_kerja[]" placeholder="jadwal kerja" class="form-control"></td>
                <td><input type="text" name="pekerjaan_yang_dilakukan[]" placeholder="pekerjaan yang dilakukan" class="form-control"></td>
                <td><input type="text" name="waktu_mulai[]" placeholder="mulai" class="form-control"></td>
                <td><input type="text" name="waktu_selesai[]" placeholder="selesai" class="form-control"></td>
                <td><input type="text" name="total_waktu[]" placeholder="total waktu" class="form-control"></td>
                <td><input type="text" name="keterangan[]" placeholder="keterangan" class="form-control"></td>
                <td><input type="text" name="tul[]" placeholder="tul" class="form-control"></td>
                <td><button class="btn btn-danger remove-table-row" type="button">Hapus</button></td>
            </tr>
            `);
        });

        $(document).on('click', '.remove-table-row', function(){
            $(this).parents('tr').remove();
        });
    });
</script>
@endsection --}}
