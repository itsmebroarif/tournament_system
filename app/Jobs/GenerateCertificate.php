<?php

namespace App\Jobs;

use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateCertificate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Registration $registration,
        public string $rank, // 'juara_1', 'juara_2', 'juara_3', 'participant'
    ) {}

    public function handle(): void
    {
        $rankLabels = [
            'juara_1' => 'Juara 1',
            'juara_2' => 'Juara 2',
            'juara_3' => 'Juara 3',
            'participant' => 'Peserta',
        ];

        $participant = $this->registration->participant;
        $competition = $this->registration->competition;

        $pdf = Pdf::loadView('pdf.certificate', [
            'name' => $participant->name,
            'competition' => $competition->name,
            'rank' => $rankLabels[$this->rank] ?? 'Peserta',
            'rankKey' => $this->rank,
            'date' => now()->locale('id')->translatedFormat('d F Y'),
        ]);

        $filename = 'certificates/' . $this->rank . '/' . $participant->id . '_' . str_replace(' ', '_', $participant->name) . '.pdf';

        Storage::disk('public')->put($filename, $pdf->output());

        $this->registration->updateQuietly([
            'certificate_path' => $filename,
        ]);
    }
}
