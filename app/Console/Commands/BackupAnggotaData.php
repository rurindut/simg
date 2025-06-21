<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anggota;
use Illuminate\Support\Facades\Storage;

class BackupAnggotaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:anggota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup data anggota dan relasi ke file JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timestamp = now()->format('Ymd_His');
        $path = "backups/anggota/{$timestamp}/";

        // Buat folder backup
        Storage::makeDirectory($path);

        $this->info('Membackup data anggota...');

        $anggota = Anggota::with([
            'baptisAnak',
            'baptisSidi',
            'atestasis',
            'ayah',
            'ibu',
            'pasangan',
            'anaks',
            'pengalamanGerejawis',
            'aktivitasSosials',
            'pekerjaans',
        ])->get();

        // Simpan dalam file JSON
        Storage::put($path . 'anggota.json', $anggota->toJson(JSON_PRETTY_PRINT));

        // Metadata
        $meta = [
            'backup_time' => now()->toDateTimeString(),
            'total_anggota' => $anggota->count(),
        ];
        Storage::put($path . 'metadata.json', json_encode($meta, JSON_PRETTY_PRINT));

        $this->info('Backup selesai di folder: ' . $path);
    }
}
