@extends('layout.template')

@section('breadcrumbs')
    Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Update Data Karyawan</h3>
    </div>

    <div class="card-body">
        <form action="{{ url('/karyawan/update/'.$karyawan->npk) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="npk">NPK</label>
                        <input type="number" class="form-control" name="npk" id="npk" value="{{ old('npk', $karyawan->npk) }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Nama Karyawan</label>
                <input type="text" class="form-control" name="nama" id="nama" value="{{ old('nama', $karyawan->nama) }}" placeholder="Nama Karyawan">
            </div>
            <div class="form-group">
                <label>Nama Karyawan</label>
                <input type="text" class="form-control" name="no_telp" id="no_telp" value="{{ old('no_telp', $karyawan->no_telp) }}" placeholder="no_telp">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select class="form-control select2" name="role" style="width: 100%;">
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ $role == $karyawan->role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Departemen</label>
                <select class="form-control select2" name="department_id" style="width: 100%;">
                    @foreach($department as $dept)
                        <option value="{{ $dept->id }}" {{ $dept->id == $karyawan->department_id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col mt-3">
                    <a href="/karyawan" class="btn btn-danger">Batal</a>
                    <button type="submit" class="btn btn-primary float-right">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
