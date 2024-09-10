@extends('layout.template')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Lembur Kolektif</h3>
        <button class="btn btn-success float-right">Upload .csv .xlsx</button>
    </div>
    <div class="card-body">
        <form action="" method="POST">
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
                                <option value="1">1</option>
                                @foreach($departements as $department)
                                    <option value="{{ $department->id }}">{{ $department->nama }}</option>
                                @endforeach
                            </select>
                        <input type="hidden" value="pending" name="current_status" id="current_status">
                        <input type="hidden" value="pending" name="current_approver" id="current_approver">
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
                    <td>
                        <input type="text" name="npk[]" id="npk" placeholder="npk" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="jadwal_kerja[]" id="jadwal_kerja" placeholder="jadwal_kerja" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="pekerjaan_yang_dilakukan[]" id="pekerjaan_yang_dilakukan" placeholder="pekerjaan_yang_dilakukan" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="waktu_mulai[]" id="" class="form-control" placeholder="mulai">
                    </td>
                    <td>
                        <input type="text" name="waktu_selesai[]" id="" class="form-control" placeholder="selesai">
                    </td>
                    <td>
                        <input type="text" name="total_waktu[]" id="" class="form-control" placeholder="total waktu">
                    </td>
                    <td>
                        <input type="text" name="keterangan[]" id="keterangan" placeholder="keterangan" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="tul[]" id="tul" placeholder="tul" class="form-control">
                        <input type="text" name="status[]" id="status" placeholder="status" class="form-control" hidden>
                    </td>
                    <td>
                        <button class="btn btn-primary" type="button" name="add" id="add">
                            tambah
                        </button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right">Ajukan</button>
        <a href="/overtime" class="btn btn-outline-primary">Kembali</a>
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
                <td>
                    <input type="text" name="npk[]" id="npk" placeholder="npk" class="form-control">
                </td>
                <td>
                    <input type="text" name="jadwal_kerja[]" id="jadwal_kerja" placeholder="jadwal_kerja" class="form-control">
                </td>
                <td>
                    <input type="text" name="pekerjaan_yang_dilakukan[]" id="pekerjaan_yang_dilakukan" placeholder="pekerjaan_yang_dilakukan" class="form-control">
                </td>
                <td>
                    <input type="text" name="waktu_mulai[]" id="" class="form-control" placeholder="mulai">
                </td>
                <td>
                     <input type="text" name="waktu_selesai[]" id="" class="form-control" placeholder="selesai">
                </td>
                <td>
                    <input type="text" name="total_waktu[]" id="" class="form-control" placeholder="total waktu">
                </td>
                <td>
                    <input type="text" name="keterangan[]" id="keterangan" placeholder="keterangan" class="form-control">
                </td>
                <td>
                    <input type="text" name="tul[]" id="tul" placeholder="tul" class="form-control">
                    <input type="text" name="status[]" id="status" placeholder="status" class="form-control" hidden>
                </td>   
                <td>
                    <button class="btn btn-danger remove-table-row" type="button">
                        hapus
                    </button>
                </td>
            </tr>
            `);
        });

        $(document).on('click', '.remove-table-row', function(){
            $(this).parents('tr').remove();
        });
    });
</script>
@endsection