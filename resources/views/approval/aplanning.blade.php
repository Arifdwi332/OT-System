@extends('layout.template')

@section('breadcrumbs')
    Approval Planning OT
@endsection

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Data Planning OT</h3>
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
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Pengajuan</th>
                    <th>Department</th>
                    <th>Hari</th>
                    <th>Tanggal</th>
                    <th>Jumlah Member</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuanData as $data)
                <tr>
                    <td>{{ $data['id'] }}</td>
                    <td>{{ $data['department'] }}</td>
                    <td>{{ $data['hari'] }}</td>
                    <td>{{ $data['tanggal'] }}</td>
                    <td>{{ $data['jumlah_member'] }}</td>
                    <td>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#verifikasiModal{{ $data['id'] }}">
                            Verifikasi
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="verifikasiModal{{ $data['id'] }}" tabindex="-1" role="dialog" aria-labelledby="verifikasiModalLabel{{ $data['id'] }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="verifikasiModalLabel{{ $data['id'] }}">Approval Planning OT ID : {{ $data['id'] }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="mb-2" style="border: none;">
                                            <tr>
                                                <td style="border: none;">Hari</td>
                                                <td style="border: none;">:</td>
                                                <td style="border: none;">{{ $data['hari'] }}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none;">Tanggal</td>
                                                <td style="border: none;">:</td>
                                                <td style="border: none;">{{ $data['tanggal'] }}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none;">Department</td>
                                                <td style="border: none;">:</td>
                                                <td style="border: none;">{{ $data['department'] }}</td>
                                            </tr>
                                        </table>
                                        <form action="{{ route('approval.planning.approve', $data['id']) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>NPK</th>
                                                        <th>Jadwal Kerja</th>
                                                        <th>Pekerjaan</th>
                                                        <th>Waktu Mulai</th>
                                                        <th>Waktu Selesai</th>
                                                        <th>Planning Waktu</th>
                                                        <th>Keterangan</th>
                                                        <th>Status</th>
                                                        <th>Approve/Reject</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data['members'] as $member)
                                                    <tr>
                                                        <td>{{ $member['npk'] }}</td>
                                                        <td>{{ $member['jadwal_kerja'] }}</td>
                                                        <td>{{ $member['pekerjaan_yang_dilakukan'] }}</td>
                                                        <td>{{ $member['waktu_mulai'] }}</td>
                                                        <td>{{ $member['waktu_selesai'] }}</td>
                                                        <td>{{ $member['planning_waktu'] }}</td>
                                                        <td>{{ $member['keterangan'] }}</td>
                                                        <td>{{ $member['planning_status'] }}</td>
                                                        <td>
                                                            <!-- Checkbox untuk Approve dengan iCheck Primary -->
                                                            <div class="icheck-success d-inline">
                                                                <input type="radio" name="status[{{ $member['npk'] }}]" id="approve_{{ $member['npk'] }}" value="approved" onclick="toggleRejectReason({{ $member['npk'] }}, false)">
                                                                <label for="approve_{{ $member['npk'] }}">
                                                                    Approve
                                                                </label>
                                                            </div>
                                                        
                                                            <!-- Checkbox untuk Reject dengan iCheck Danger -->
                                                            <div class="icheck-danger d-inline">
                                                                <input type="radio" name="status[{{ $member['npk'] }}]" id="reject_{{ $member['npk'] }}" value="rejected" onclick="toggleRejectReason({{ $member['npk'] }}, true)">
                                                                <label for="reject_{{ $member['npk'] }}">
                                                                    Reject
                                                                </label>
                                                            </div>
                                                        
                                                            <!-- Field untuk Alasan Reject (hanya muncul jika Reject dipilih) -->
                                                            <input type="text" name="reject_reason[{{ $member['npk'] }}]" id="reject_reason_{{ $member['npk'] }}" class="form-control mt-2" placeholder="Alasan Reject" style="display: none;">
                                                        </td>                                                                                                             
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleRejectReason(npk, show) {
        const rejectReasonField = document.getElementById('reject_reason_' + npk);
        if (show) {
            rejectReasonField.style.display = 'block';
        } else {
            rejectReasonField.style.display = 'none';
            rejectReasonField.value = ''; // Kosongkan alasan reject jika approve dipilih
        }
    }
    </script>    
@endsection
