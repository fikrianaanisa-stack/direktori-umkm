@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <h1>Dashboard</h1>

    <p>
        Selamat datang,
        <strong>{{ auth()->user()->name }}</strong>!
    </p>

    <p>
        Anda login sebagai
        <strong>
            {{ auth()->user()->role === 'super_admin'
                ? 'Super Admin'
                : 'Admin' }}
        </strong>.
    </p>

    <hr>

    <h2>Ringkasan Data</h2>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-top: 20px;
    ">

        <div style="background: white; padding: 25px; border-radius: 10px;">
            <h3>Total UMKM</h3>
            <h1>{{ $totalUmkm }}</h1>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px;">
            <h3>Total Produk</h3>
            <h1>{{ $totalProduk }}</h1>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px;">
            <h3>Total Pengunjung</h3>
            <h1>{{ $totalPengunjung }}</h1>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px;">
            <h3>Total Pengelola</h3>
            <h1>{{ $totalPengelola }}</h1>
        </div>

    </div>

@endsection
