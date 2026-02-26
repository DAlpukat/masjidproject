<table class="w-full text-left border-collapse">
    <thead class="bg-slate-50/50 border-b border-slate-100">
        <tr>
            <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
            <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500">Keterangan</th>
            <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500">User</th>
            <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">Jenis</th>
            <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Jumlah</th>
            <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right w-24">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
        @forelse($laporans as $laporan)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="p-4 text-sm text-slate-600 whitespace-nowrap">
                    {{ $laporan->tanggal->format('d M Y') }}
                </td>
                <td class="p-4">
                    <div class="text-sm font-semibold text-slate-800">{{ $laporan->keterangan }}</div>
                    <div class="text-xs text-slate-400 capitalize mt-1">{{ $laporan->sifat_transaksi }}</div>
                </td>
                <td class="p-4 text-sm text-slate-600">
                    @if($laporan->sifat_transaksi == 'personal')
                        {{ $laporan->user->name ?? '-' }}
                    @else
                        <span class="px-2 py-1 bg-slate-100 rounded text-xs text-slate-500">Umum</span>
                    @endif
                </td>
                <td class="p-4 text-center">
                    @if($laporan->jenis == 'masuk')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            Masuk
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Keluar
                        </span>
                    @endif
                </td>
                <td class="p-4 text-right text-sm font-bold font-mono text-slate-700">
                    Rp {{ number_format($laporan->jumlah, 0, ',', '.') }}
                </td>
                <td class="p-4 text-right">
                    <!-- Cek Admin: Hanya Admin yang bisa hapus -->
                    @if($isAdmin)
                        <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" onsubmit="return confirm('Yakin hapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600 transition-colors p-2 hover:bg-red-50 rounded-lg" title="Hapus Laporan">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    @else
                        <span class="text-slate-300 text-xs">-</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="p-8 text-center text-slate-400">
                    Belum ada data transaksi.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>