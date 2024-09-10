@extends('layout.template')

@section('breadcrumbs')
Dashboard {{ Auth::user()->role }}
@endsection

@section('content')
<div class="row">
    <div class="col">
        <h4>Selamat Datanag {{ Auth::user()->nama }}</h3>
    </div>
</div>
@endsection