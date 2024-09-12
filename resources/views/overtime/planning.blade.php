@extends('layout.template')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Lembur Kolektif</h3>
        <button class="btn btn-success float-right">Import .xlsx</button>
    </div>
    <div class="card-body">
        <form action="{{ route('overtime.store') }}" method="POST">
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
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            $('#reservationdate').on('change.datetimepicker', function(e) {
                                const selectedDate = new Date(e.date);
                                const hari = selectedDate.toLocaleDateString('id-ID', { weekday: 'long' });
                                document.getElementById('hari').value = capitalizeFirstLetter(hari);
                            });
                    
                            function capitalizeFirstLetter(string) {
                                return string.charAt(0).toUpperCase() + string.slice(1);
                            }
                        });
                    </script>
                    <td>
                        <label>Hari</label>
                        <input class="form-control" type="text" id="hari" placeholder="hari" readonly>
                    </td>
                    <td>
                        <label>Departemen</label>
                        <select class="form-control select2bs4" style="width: 100%;" name="department_id">
                            @foreach($department as $department)
                                <option value="{{ $department->id }}">{{ $department->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <input type="text" name="current_status" value="" hidden>
                    <input type="text" name="current_approver" value="" hidden>
                    <input type="text" name="status" value="" hidden>
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
                        <td width="8%">
                            <select name="npk[]" class="form-control select2bs4 npk-select">
                                @foreach($apiData as $karyawan)
                                <option value="{{ $karyawan['npk_akses'] }}">{{ $karyawan['npk_akses'] }}</option>
                            @endforeach
                            </select>
                        </td>
                        <td width="8%">
                            <select name="jadwal_kerja[]" class="form-control select2bs4" >
                                @foreach($jadwalKerja as $jd)
                                    <option value="{{ $jd }}">{{ ucfirst($jd) }}</option>
                            @endforeach
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
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const timeInputs = document.querySelectorAll('.waktu_mulai, .waktu_selesai');
                        
                                timeInputs.forEach(input => {
                                    input.addEventListener('change', function () {
                                        const row = this.closest('tr');
                                        const waktuMulai = row.querySelector('.waktu_mulai').value;
                                        const waktuSelesai = row.querySelector('.waktu_selesai').value;
                        
                                        if (waktuMulai && waktuSelesai) {
                                            const mulai = new Date(`1970-01-01T${waktuMulai}:00`);
                                            const selesai = new Date(`1970-01-01T${waktuSelesai}:00`);
                                            
                                            let totalWaktu = (selesai - mulai) / 1000 / 60 / 60; // Hasil dalam jam
                        
                                            // Jika waktu selesai lebih kecil dari waktu mulai (misalnya lembur melewati tengah malam)
                                            if (totalWaktu < 0) {
                                                totalWaktu += 24;
                                            }
                        
                                            row.querySelector('.total_waktu').value = totalWaktu.toFixed(2);
                                        }
                                    });
                                });
                            });
                        </script>
                        <td>
                            <input type="number" name="total_waktu[]" placeholder="total waktu" class="form-control total_waktu" readonly>
                        </td>
                        <td width="16%">
                            <textarea name="keterangan[]" placeholder="pekerjaan yang dilakukan" class="form-control" cols="10" rows="0"></textarea>
                        </td>
                        <td>
                            <input type="text" name="tul[]" placeholder="tul" class="form-control">
                            <input type="text" name="status" hidden>
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
@endsection 
