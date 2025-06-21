<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakAnggotaController extends Controller
{
    public function cetak(Anggota $record)
    {
        $pdf = Pdf::loadView('pdf.anggota', compact('record'));

        return $pdf->download('data-anggota-' . $record->nia . '.pdf');
        // Atau jika ingin tampil di browser:
        // return $pdf->stream('data-anggota-' . $record->nia . '.pdf');
    }
}
