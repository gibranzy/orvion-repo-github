@extends('layouts.admin')
@section('title', 'Tambah Pengguna')
@section('content')
<h2 class="text-2xl font-bold mb-6">Tambah Pengguna Baru</h2>
<div class="bg-white p-6 rounded-xl shadow-sm border">
    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
        @csrf
        <div><label class="block text-sm font-medium mb-1">Nama</label><input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required></div>
        <div><label class="block text-sm font-medium mb-1">Email</label><input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">Password</label><input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required></div>
            <div><label class="block text-sm font-medium mb-1">Konfirmasi Password</label><input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none" required></div>
        </div>
        <div><label class="block text-sm font-medium mb-1">Telepon</label><input type="text" name="phone" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></div>
        <div class="flex gap-3"><button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg">Simpan</button><a href="{{ route('admin.users.index') }}" class="px-6 py-2 border rounded-lg">Batal</a></div>
    </form>
</div>
@endsection