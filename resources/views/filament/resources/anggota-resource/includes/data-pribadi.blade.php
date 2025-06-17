<div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
    {{-- Kolom 1 --}}
    <div class="space-y-2">
        <p><span class="font-semibold">Nama lengkap</span><br>{{ $record->nama ?? '-' }}</p>
        <p><span class="font-semibold">Panggilan</span><br>{{ $record->nama_panggilan ?? '-' }}</p>
        <p><span class="font-semibold">Sapaan</span><br>{{ $record->sapaan ?? '-' }}</p>
        <p><span class="font-semibold">Tempat, Tgl Lahir</span><br>{{ $record->tempat_lahir }}, {{ \Carbon\Carbon::parse($record->tanggal_lahir)->translatedFormat('d M Y') }}</p>
        <p><span class="font-semibold">Golongan Darah</span><br>
            {{ $record->golongan_darah ?? '-' }}
            @if (! $record->bersedia_donor)
                <span class="text-gray-500 text-xs">(Tidak Bersedia Donor)</span>
            @endif
        </p>
        <p><span class="font-semibold">Tanggal Registrasi</span><br>{{ \Carbon\Carbon::parse($record->tanggal_registrasi)->translatedFormat('d M Y') }}</p>
    </div>

    {{-- Kolom 2 --}}
    <div class="space-y-2">
        <p><span class="font-semibold">Status Tinggal</span><br>{{ $record->status_tinggal ?? '-' }}</p>
        <p><span class="font-semibold">Alamat KTP</span><br>{{ $record->alamat_ktp ?? '-' }}</p>
        <p><span class="font-semibold">Alamat Domisili</span><br>{{ $record->alamat_domisili ?? '-' }}</p>
        <p><span class="font-semibold">Email</span><br>{{ $record->email ?? '-' }}</p>
        <p><span class="font-semibold">No Hp</span><br>{{ $record->no_hp ?? '-' }}</p>
        <p><span class="font-semibold">Telpon</span><br>{{ $record->telpon ?? '-' }}</p>
    </div>

    {{-- Kolom 3 --}}
    <div class="space-y-2">
        <p><span class="font-semibold">Pendidikan Terakhir</span><br>{{ $record->pendidikan_terakhir ?? '-' }}</p>
        <p><span class="font-semibold">Disiplin Ilmu</span><br>{{ $record->disiplin_ilmu ?? '-' }}</p>
        <p><span class="font-semibold">Jurusan</span><br>{{ $record->jurusan ?? '-' }}</p>
        <p><span class="font-semibold">Gelar</span><br>{{ $record->gelar ?? '-' }}</p>
        <p><span class="font-semibold">Lokasi</span><br>{{ $record->lokasi ?? 'Data peta tidak valid!' }}</p>
    </div>
</div>
