<!DOCTYPE html>
<html>
<head>
    <title>Data Anggota - {{ $record->nama }}</title>
    <style>
        @page {
            margin: 2cm;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 30px;
        }

        h2 {
            font-size: 16px;
            border-bottom: 1px solid #333;
            padding-bottom: 4px;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .info p,
        .section p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 4px 6px;
            text-align: left;
        }

        .two-column {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .column {
            width: 48%;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .text-muted {
            color: #666;
        }

        /* Style khusus untuk cetak */
        @media print {
            .page-break {
                page-break-before: always;
            }
        }
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
    <div class="section page-break">
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
