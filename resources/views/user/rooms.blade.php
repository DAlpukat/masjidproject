@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Ruangan Saya</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">
                            Nama Tempat
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">
                            Kode / Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <p class="text-gray-900 font-bold">{{ $room->nama }}</p>
                                <p class="text-gray-600 text-xs">{{ $room->deskripsi }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                @if($room->is_public)
                                    <span class="text-green-600">Public</span>
                                @else
                                    <span class="text-blue-600 font-mono">{{ $room->kode_referral }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 text-sm">
                                <a href="{{ route('room.view', $room->slug) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                                    Masuk Room
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-gray-500">
                                Kamu belum bergabung ke manapun. Gunakan menu "+ Gabung Room" di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection