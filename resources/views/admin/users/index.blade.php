@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>
    <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium"><i class="fas fa-plus mr-2"></i>Tambah User</a>
</div>
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-sm uppercase"><tr><th class="px-6 py-3">Nama</th><th class="px-6 py-3 hidden md:table-cell">Email</th><th class="px-6 py-3 hidden lg:table-cell">Telepon</th><th class="px-6 py-3">Aksi</th></tr></thead>
            <tbody class="divide-y">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 hidden md:table-cell">{{ $user->email }}</td>
                    <td class="px-6 py-4 hidden lg:table-cell">{{ $user->phone ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm hover:bg-red-200"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada user</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">{{ $users->links() }}</div>
</div>
@endsection