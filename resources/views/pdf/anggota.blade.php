<!DOCTYPE html>
<html>
<head>
    <title>Formulir Data Anggota Jemaat</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
        }

        h1 {
            text-align: center;
            font-size: 14pt;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td, th {
            vertical-align: top;
            padding: 4px 6px;
        }

        .label {
            font-weight: bold;
            width: 20%;
            white-space: nowrap;
        }

        .value {
            border-bottom: 1px dotted #000;
            min-height: 14px;
        }

        .form-box {
            border: 1px solid #000;
            padding: 8px;
            font-size: 10px;
            float: right;
            width: 180px;
            margin-top: -50px;
        }

        .photo-box {
            border: 1px solid #000;
            padding: 8px;
            font-size: 10px;
            float: right;
            width: 180px;
            /* margin-top: -50px; */
        }

        .section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .checkbox-group {
            margin-top: 4px;
            margin-bottom: 10px;
        }

        .checkbox {
            display: inline-block;
            min-width: 100px;
        }

        .underline {
            border-bottom: 1px dotted  #000;
            display: inline-block;
            min-width: 200px;
            padding: 2px 4px;
        }

        .box {
            border: 1px solid #000;
            padding: 10px;
            font-size: 10px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <h1>FORMULIR PENDATAAN ANGGOTA JEMAAT</h1>

    {{-- Header Info Gereja --}}
    <table>
        <tr>
            <td class="label">Gereja:</td>
            <td class="value">{{ $record->organization?->name ?? '' }}</td>
            <td rowspan="11">
                @php
                    $fotoPath = $record->foto_anda ? public_path('storage/' . $record->foto_anda) : null;
                @endphp

                <div class="photo-box" style="width: 3cm; height: 4cm; border: 1px solid #000; text-align: center; font-size: 10px;">
                @if ($fotoPath && file_exists($fotoPath) && is_file($fotoPath))
                    <img src="file://{{ $fotoPath }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Foto Anggota">
                @else
                    Foto Anggota<br>
                @endif
                </div>
            </td>
        </tr>
        <tr>
            <td class="label">Nama Lengkap:</td>
            <td class="value">{{ $record->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status Jemaat:</td>
            <td class="value">{{ $record->status_jemaat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIA:</td>
            <td class="value">{{ $record->nia ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIK:</td>
            <td class="value">{{ $record->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Panggilan:</td>
            <td class="value">{{ $record->panggilan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin:</td>
            <td class="value">{{ ucfirst($record->jenis_kelamin) ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Suku:</td>
            <td class="value">{{ $record->suku?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat / Tgl Lahir:</td>
            <td class="value">{{ $record->tempat_lahir ?? '-' }} / {{ \Carbon\Carbon::parse($record->tanggal_lahir)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Status Perkawinan:</td>
            <td class="value">{{ ucfirst($record->status_perkawinan) ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Registrasi:</td>
            <td class="value">{{ \Carbon\Carbon::parse($record->tanggal_registrasi)->translatedFormat('d M Y') }}</td>
        </tr>
    </table>

    <div class="section">
        <table>
            <tr>
                <td class="label">Alamat KTP:</td>
                <td class="value" colspan="3">{{ $record->alamat_ktp ?? '-' }}</td>
                <td class="label">Kecamatan KTP:</td>
                <td class="value" colspan="3">{{ $record->kecamatan_ktp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Domisili:</td>
                <td class="value" colspan="3">{{ $record->alamat_domisili ?? '-' }}</td>
                <td class="label">Kecamatan Domisili:</td>
                <td class="value" colspan="3">{{ $record->kecamatan_domisili ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td class="label">Telepon:</td>
                <td class="value">{{ $record->telepon ?? '-' }}</td>
                <td class="label">HP:</td>
                <td class="value">{{ $record->nomor_hp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td class="value" colspan="3">{{ $record->email ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td class="label">Wilayah:</td>
                <td class="value">{{ $record->wilayah?->nama ?? '-' }}</td>
                <td class="label">Kelompok:</td>
                <td class="value">{{ $record->kelompok?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Suku:</td>
                <td class="value" colspan="3">{{ $record->suku?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status Tinggal:</td>
                <td class="value">{{ ucfirst($record->status_tinggal)?? '-' }}</td>
                <td class="label">Status Hidup:</td>
                <td class="value">{{ ucfirst($record->status_hidup) ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td class="label">Hobi:</td>
                <td class="value">{{ $record->hobis->pluck('name')->implode(', ') ?: '-' }}</td>
                <td class="label">Minat:</td>
                <td class="value">{{ $record->minats->pluck('name')->implode(', ') ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Golongan Darah:</td>
                <td class="value" colspan="3">{{ $record->golongan_darah ?? '-' }}
                    @if (! $record->bersedia_donor)
                        <span class="text-gray-500 text-xs">(Tidak Bersedia Donor)</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td class="label">Pendidikan Terakhir:</td>
                <td class="value">{{ $record->pendidikan?->name ?? '-' }}</td>
                <td class="label">Disiplin Ilmu:</td>
                <td class="value">{{ $record->disiplin_ilmu ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jurusan:</td>
                <td class="value">{{ $record->jurusan ?? '-' }}</td>
                <td class="label">Gelar:</td>
                <td class="value">{{ $record->gelar ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td class="label">Foto Keluarga:</td>
                <td>
                @php
                    $fotoKeluargaPath = $record->foto_keluarga ? public_path('storage/' . $record->foto_keluarga) : null;
                @endphp

                <div style="width: 3cm; height: 4cm; border: 1px solid #000; text-align: center; font-size: 10px;">
                @if ($fotoKeluargaPath && file_exists($fotoKeluargaPath) && is_file($fotoKeluargaPath))
                    <img src="file://{{ $fotoKeluargaPath }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Foto Keluarga">
                @else
                    Foto Anggota<br>
                @endif
                </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <h2 style="margin-top: 40px;">KEANGGOTAAN GEREJA (Bagi yang sudah baptis / sidi)</h2>

    <div class="section">
    <table class="form-table">
        <tr><td colspan="2"><strong>BAPTIS ANAK</strong></td></tr>
        <tr>
            <td class="label">Tempat Baptis:</td>
            <td class="value">{{ $record->baptisAnak->tempat ?? '-' }}</td>
            <td class="label">Nomor Piagam:</td>
            <td class="value">{{ $record->baptisAnak->no_piagam ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Baptis Anak:</td>
            <td class="value">{{ optional($record->baptisanAnak)?->tanggal ? \Carbon\Carbon::parse($record->baptisanAnak->tanggal)->format('d/m/Y') : '' }}</td>
            <td class="label">Oleh Pendeta:</td>
            <td class="value">{{ $record->baptisanAnak?->pendeta ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Jemaat (Gereja):</td>
            <td class="value">{{ $record->baptisanAnak?->gereja ?? '' }}</td>
            <td class="label">Alamat (Gereja):</td>
            <td class="value">{{ $record->baptisanAnak?->alamat_gereja ?? '' }}</td>
        </tr>
    </table>
    <table class="form-table">
        <tr><td colspan="2" style="padding-top: 20px"><strong>BAPTIS SIDI</strong></td></tr>
        <!-- <tr>
            <td class="label">Jenis:</td>
            <td colspan="3">
                <div class="checkbox-group">
                    [{{ $record->baptisSidi ? '✓' : ' ' }}] Baptis Dewasa
                    [{{ $record->baptisSidi?->jenis === 'sidi' ? '✓' : ' ' }}] Sidi
                </div>
            </td>
        </tr> -->
        <tr>
            <td class="label">Tempat Baptis:</td>
            <td class="value">{{ $record->baptisSidi->tempat ?? '-' }}</td>
            <td class="label">Nomor Piagam:</td>
            <td class="value">{{ $record->baptisSidi->no_piagam ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Baptis:</td>
            <td class="value">{{ optional($record->baptisSidi)?->tanggal ? \Carbon\Carbon::parse($record->baptisSidi->tanggal)->format('d/m/Y') : '' }}</td>
            <td class="label">Oleh Pendeta:</td>
            <td class="value">{{ $record->baptisSidi?->pendeta ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Jemaat (Gereja):</td>
            <td class="value">{{ $record->baptisSidi?->gereja ?? '' }}</td>
            <td class="label">Alamat (Gereja):</td>
            <td class="value">{{ $record->baptisSidi?->alamat_gereja ?? '' }}</td>
        </tr>
    </table>
    </div>

    <br><br>
    <h2>ATESASI / MUTASI (Perpindahan Anggota Jemaat sebelumnya)</h2>

    <div class="section">
    <table class="mutasi-table" style="width: 100%; border-collapse: collapse; font-size: 11px;" border="1">
        <thead>
            <tr>
                <th>Tipe</th>
                <th>Tanggal</th>
                <th>Gereja Asal</th>
                <th>Alamat Asal</th>
                <th>Gereja Tujuan</th>
                <th>Alamat Tujuan</th>
                <th>Nomor Surat</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($record->atestasis as $atestasi)
                <tr>
                    <td>{{ $atestasi->tipe }}</td>
                    <td>{{ \Carbon\Carbon::parse($atestasi->tanggal)->format('d M Y') }}</td>
                    <td>{{ $atestasi->gereja_dari }}</td>
                    <td>{{ $atestasi->alamat_asal }}</td>
                    <td>{{ $atestasi->gereja_tujuan }}</td>
                    <td>{{ $atestasi->alamat_tujuan }}</td>
                    <td>{{ $atestasi->nomor_surat }}</td>
                    <td>{{ $atestasi->alasan }}</td>
                </tr>
            @empty
                @for ($i = 0; $i < 3; $i++)
                    <tr>
                        <td style="height: 30px"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="page-break"></div>

    <h2 style="margin-top: 40px;">DATA KELUARGA</h2>

    <!-- Orang Tua -->
    <div class="section">
    <table class="form-table">
        <!-- <tr><td style="padding-top: 20px"><strong>Ayah</strong></td><td style="padding-top: 20px"><strong>Ibu</strong></td></tr> -->
        <tr>
            <td class="label">Nama Ayah:</td>
            <td class="value">{{ $record->ayah?->nama ?? '-' }}</td>
            <td class="label">NIA Ayah:</td>
            <td class="value">{{ $record->ayah->nia ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Ibu:</td>
            <td class="value">{{ $record->ibu?->nama ?? '-' }}</td>
            <td class="label">NIA Ibu:</td>
            <td class="value">{{ $record->ibu->nia ?? '-' }}</td>
        </tr>
    </table>
    </div>

    <!-- Pernikahan -->
    <div class="section">
        <h4 style="margin-top: 20px;">JIKA MENIKAH (atau pernah menikah)</h4>
        <table class="form-table">
            <tr>
                <td class="label">Nama Istri / Suami:</td>
                <td class="value">{{ $record->pasangan?->nama ?? '-' }}</td>
                <td class="label">Tanggal Catatan Sipil:</td>
                <td class="value">{{ $record->pasangan?->tanggal_catatan_sipil ? \Carbon\Carbon::parse($record->pasangan->tanggal_catatan_sipil)->format('d M Y') : '-' }}]</td>
            </tr>
            <tr>
                <td class="label">No. Akta Nikah:</td>
                <td class="value">{{ $record->pasangan?->nomor_akta_nikah ?? '-' }}</td>
                <td class="label">Tempat Catatan Sipil:</td>
                <td class="value">{{ $record->pasangan?->tempat_catatan_sipil ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Pemberkatan -->
    <div class="section">
        <h4 style="margin-top: 20px;">PEMBERKATAN</h4>
        <table class="form-table">
            <tr>
                <td class="label">Tanggal:</td>
                <td class="value">{{ $record->pasangan?->tanggal_pemberkatan ? \Carbon\Carbon::parse($record->pasangan->tanggal_pemberkatan)->format('d/m/Y') : '-' }}</td>
                <td class="label">Oleh Pendeta:</td>
                <td class="value">{{ $record->pasangan?->pendeta_pemberkatan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. Piagam:</td>
                <td class="value">{{ $record->pasangan?->no_piagam ?? '-' }}</td>
                <td class="label">Gereja:</td>
                <td class="value">{{ $record->pasangan?->gereja ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Gereja:</td>
                <td class="value" colspan="3">{{ $record->pasangan?->alamat_gereja ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Anak-anak -->
    <h4 style="margin-top: 30px;">ANAK (jika ada)</h4>

    <div class="section">
    <table class="form-table" style="width: 100%; border-collapse: collapse; font-size: 11px;" border="1">
        <thead>
            <tr>
                <th style="padding: 5px;">Anak ke</th>
                <th style="padding: 5px;">Nama</th>
                <th style="padding: 5px;">NIA</th>
                <th style="padding: 5px;">L/P</th>
                <th style="padding: 5px;">Tempat, Tgl Lahir</th>
                <th style="padding: 5px;">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($record->anaks as $i => $anak)
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td>{{ $anak->nama }}</td>
                    <td>{{ $anak->nia ?? '-'}}</td>
                    <td style="text-align: center;">{{ $anak->jenis_kelamin ?? '-' }}</td>
                    <td>{{ $anak->tempat_lahir ?? '-' }}, {{ $anak->tanggal_lahir ? \Carbon\Carbon::parse($anak->tanggal_lahir)->translatedFormat('d M Y') : '' }}</td>
                    <td>{{ $anak->alamat ?? '-' }}</td>
                </tr>
            @empty
                @for ($i = 1; $i <= 2; $i++)
                    <tr>
                        <td style="text-align: center;">{{ $i }}</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="page-break"></div>

    <h2 style="margin-top: 40px;">PENGALAMAN / PEKERJAAN / AKTIVITAS</h2>
    <h4 style="margin-top: 30px;">Pengalaman Gerejawi</h4>
    <div class="section">
    <table style="width: 100%; border-collapse: collapse; font-size: 11px;" border="1">
        <thead>
            <tr>
                <th style="padding: 4px;">Bidang Pelayanan / Lembaga / Komisi</th>
                <th style="padding: 4px;">Jabatan / Keterangan Tugas</th>
                <th style="padding: 4px;">Periode Tahun</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($record->pengalamanGerejawis as $gerejawi)
                <tr>
                    <td style="padding: 4px;">{{ $gerejawi->bidang ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $gerejawi->jabatan ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $gerejawi->tahun_mulai ?? 'N/a' }} - {{ $gerejawi->tahun_selesai ?? 'N/a' }}</td>
                </tr>
            @empty
                @for ($i = 0; $i < 3; $i++)
                    <tr>
                        <td style="padding: 16px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            @endforelse
        </tbody>
    </table>
    </div>
    <h4 style="margin-top: 30px;">Aktivitas Sosial</h4>
    <div class="section">
    <table style="width: 100%; border-collapse: collapse; font-size: 11px;" border="1">
        <thead>
            <tr>
                <th style="padding: 4px;">Organisasi</th>
                <th style="padding: 4px;">Kegiatan</th>
                <th style="padding: 4px;">Jabatan</th>
                <th style="padding: 4px;">Periode</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($record->aktivitasSosials as $aktivitas)
                <tr>
                    <td style="padding: 4px;">{{ $aktivitas->organisasi ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $aktivitas->kegiatan ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $aktivitas->jabatan ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $aktivitas->tahun_mulai ?? 'N/a' }} - {{ $aktivitas->tahun_selesai ?? 'N/a' }}</td>
                </tr>
            @empty
                @for ($i = 0; $i < 3; $i++)
                    <tr>
                        <td style="padding: 16px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            @endforelse
        </tbody>
    </table>
    </div>
    <h4 style="margin-top: 30px;">Pekerjaan</h4>
    <div class="section">
    <table style="width: 100%; border-collapse: collapse; font-size: 11px;" border="1">
        <thead>
            <tr>
                <th style="padding: 4px;">Profesi</th>
                <th style="padding: 4px;">Kantor</th>
                <th style="padding: 4px;">Alamat</th>
                <th style="padding: 4px;">Bagian</th>
                <th style="padding: 4px;">Jabatan</th>
                <th style="padding: 4px;">Tahun</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($record->pekerjaans as $pekerjaan)
                <tr>
                    <td style="padding: 4px;">{{ $pekerjaan->profesi ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $pekerjaan->kantor ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $pekerjaan->alamat ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $pekerjaan->bagian ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $pekerjaan->jabatan ?? '-' }}</td>
                    <td style="padding: 4px;">{{ $pekerjaan->tahun_mulai ?? 'N/a' }} - {{ $pekerjaan->tahun_selesai ?? 'N/a' }}</td>
                </tr>
            @empty
                @for ($i = 0; $i < 3; $i++)
                    <tr>
                        <td style="padding: 16px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            @endforelse
        </tbody>
    </table>
    </div>
</body>
</html>
