<!DOCTYPE html>
<html>
<head>
    <title>Data Anggota - {{ $record->nama }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1, h2 { margin-top: 20px; }
        .section { margin-bottom: 20px; }
        .info p { margin: 2px 0; }
    </style>
</head>
<body>

    <h1>Data Anggota - {{ $record->nama }}</h1>

    {{-- Rangkuman Panel Kiri --}}
    <div class="section info">
        <h2>Identitas Ringkas</h2>
        @include('filament.resources.anggota-resource.includes.sidepanel-anggota')
    </div>

    {{-- Tab: Data Pribadi --}}
    <div class="section">
        <h2>Data Pribadi</h2>
        @include('filament.resources.anggota-resource.includes.data-pribadi')
    </div>

    {{-- Tab: Baptis --}}
    <div class="section">
        <h2>Data Baptis</h2>
        @include('filament.resources.anggota-resource.includes.data-baptis')
    </div>

    {{-- Tab: Atestasi --}}
    <div class="section">
        <h2>Data Atestasi</h2>
        @include('filament.resources.anggota-resource.includes.data-atestasi')
    </div>

    {{-- Tab: Keluarga --}}
    <div class="section">
        <h2>Data Keluarga</h2>
        @include('filament.resources.anggota-resource.includes.data-keluarga')
    </div>

    {{-- Tab: Aktivitas --}}
    <div class="section">
        <h2>Aktivitas Gerejawi, Sosial, dan Pekerjaan</h2>
        @include('filament.resources.anggota-resource.includes.data-aktivitas')
    </div>

</body>
</html>
