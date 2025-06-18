<div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
    {{-- Kolom 1 --}}
    <div class="space-y-2">
        <p><span class="font-semibold">NIK</span><br>{{ $record->nik ?? '-' }}</p>
        <p><span class="font-semibold">Sapaan</span><br>{{ ucfirst($record->sapaan) ?? '-' }}</p>
        <p><span class="font-semibold">Nama Lengkap</span><br>{{ $record->nama ?? '-' }}</p>
        <p><span class="font-semibold">Panggilan</span><br>{{ $record->nama_panggilan ?? '-' }}</p>
        <p><span class="font-semibold">Tempat, Tgl Lahir</span><br>{{ $record->tempat_lahir }}, {{ \Carbon\Carbon::parse($record->tanggal_lahir)->translatedFormat('d M Y') }}</p>
        <p><span class="font-semibold">Status Perkawinan</span><br>{{ ucfirst($record->status_perkawinan) ?? '-' }}</p>
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
        <p><span class="font-semibold">Email</span><br>{{ $record->email ?? '-' }}</p>
        <p><span class="font-semibold">No Hp</span><br>{{ $record->no_hp ?? '-' }}</p>
        <p><span class="font-semibold">Telpon</span><br>{{ $record->telpon ?? '-' }}</p>
        <p><span class="font-semibold">Alamat KTP</span><br>{{ $record->alamat_ktp ?? '-' }}</p>
        <p><span class="font-semibold">Kecamatan KTP</span><br>{{ $record->kecamatan_ktp ?? '-' }}</p>
        <p><span class="font-semibold">Alamat Domisili</span><br>{{ $record->alamat_domisili ?? '-' }}</p>
        <p><span class="font-semibold">Kecamatan Domisili</span><br>{{ $record->kecamatan_domisili ?? '-' }}</p>
    </div>

    {{-- Kolom 3 --}}
    <div class="space-y-2">
        <p><span class="font-semibold">Pendidikan Terakhir</span><br>{{ $record->pendidikan_terakhir ?? '-' }}</p>
        <p><span class="font-semibold">Disiplin Ilmu</span><br>{{ $record->disiplin_ilmu ?? '-' }}</p>
        <p><span class="font-semibold">Jurusan</span><br>{{ $record->jurusan ?? '-' }}</p>
        <p><span class="font-semibold">Gelar</span><br>{{ $record->gelar ?? '-' }}</p>
        <p><span class="font-semibold">Hobi</span><br>{{ $record->hobi?->name ?? '-' }}</p>
        <p><span class="font-semibold">Minat</span><br>{{ $record->minat?->name ?? '-' }}</p>
        <p><span class="font-semibold">Lokasi</span><br>{{ $record->lokasi ?? 'Data peta tidak valid!' }}</p>
    </div>
</div>

<div class="mt-6">
    <label class="font-bold text-sm text-gray-700 block mb-2">Foto:</label>
    <div class="flex gap-4">
        @if ($record->foto_anda)
            <div class="w-32 h-32 rounded-xl overflow-hidden border shadow">
                <img src="{{ Storage::url($record->foto_anda) }}" alt="Foto Anggota" class="object-cover w-full h-full" />
                <p class="text-xs text-center mt-1 text-gray-600">Anggota</p>
            </div>
        @endif

        @if ($record->foto_keluarga)
            <div class="w-32 h-32 rounded-xl overflow-hidden border shadow">
                <img src="{{ Storage::url($record->foto_keluarga) }}" alt="Foto Keluarga" class="object-cover w-full h-full" />
                <p class="text-xs text-center mt-1 text-gray-600">Keluarga</p>
            </div>
        @endif
    </div>
</div>
