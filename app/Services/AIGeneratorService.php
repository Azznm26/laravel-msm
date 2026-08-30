<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIGeneratorService
{
    public function generateQuiz($jabatan, $kompetensi, $jumlahSoal = 5)
    {
        $prompt = "Buatkan {$jumlahSoal} pertanyaan pilihan ganda tingkat profesional untuk menguji kompetensi '{$kompetensi}' pada posisi '{$jabatan}'. " .
            "Respons wajib berupa JSON array murni tanpa blok markdown ```json, dengan struktur persis seperti ini: " .
            '[{"pertanyaan": "...", "pilihan_a": "...", "pilihan_b": "...", "pilihan_c": "...", "pilihan_d": "...", "jawaban_benar": "a", "skor": 10}]';

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'openai/gpt-oss-120b',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Kamu adalah generator soal kuis. Selalu balas HANYA dengan JSON array murni, tanpa teks tambahan apapun, tanpa blok markdown.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            $aiText = $response->json('choices.0.message.content');
            $cleanJson = str_replace(['```json', '```'], '', $aiText);

            return json_decode(trim($cleanJson), true);
        }

        \Log::error('Groq API gagal', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return null;
    }
}
