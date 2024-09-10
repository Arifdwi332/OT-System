    @extends('layout.template')

    @section('breadcrumbs')
        Dashboard {{ Auth::user()->role }}
    @endsection

    @section('content')
    <div class="card">
        <div class="card-header">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <h3 class="card-title">Data Karyawan</h3>
            <button type="button" class="btn btn-success float-right d-none d-sm-block" data-toggle="modal" data-target="#modal-tambahUser">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </button>
        </div>

        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                <th>No</th>
                <th>NPK</th>
                <th>Nama</th>
                <th>No. Telp</th>
                <th>Departemen</th>
                <th>Role</th>
                <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($karyawanData as $dt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>NP - {{ $dt->npk }}</td>
                            <td>{{ $dt->nama }}</td>
                            <td>{{ $dt->no_telp}}</td>
                            <td>{{ $dt->department->nama }}</td>
                            <td>{{ $dt->role }}</td>
                            <td>
                                <div class="btn-group">
                                <!-- Button View -->
                                <a href="{{ url('/karyawan/detail/'.$dt->npk)}}" class="btn btn-warning"><i class="fas fa-eye"></i></a>
                                <a href="{{ url('/karyawan/edit/'.$dt->npk)}}" class="btn btn-success"><i class="fas fa-edit"></i></a>
                                <form action="{{ url('/karyawan/delete/'.$dt->npk) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data {{ $dt->nama }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data karyawan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <th>No</th>
                    <th>NPK</th>
                    <th>Nama</th>
                    <th>No. Telp</th>
                    <th>Departemen</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="card-footer"></div>

        {{-- Modal Tambah User --}}
        <div class="modal fade" id="modal-tambahUser">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Tambah Karyawan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('karyawan') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="npk">NPK</label>
                                    <input type="number" class="form-control" name="npk" id="npk" placeholder="NPK">
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
                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Karyawan">
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="number" class="form-control" name="no_telp" id="no_telp" placeholder="Nomor Telepon">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control select2" name="role" style="width: 100%;">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Departemen</label>
                            <select class="form-control select2" name="department_id" style="width: 100%;">
                                @foreach($department as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col mt-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" name="submit" class="btn btn-primary float-right">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection