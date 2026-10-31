<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Evidence;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ReportSeeder extends Seeder
{
    /**
     * Laporan demo yang menutup keempat status dan beberapa wilayah.
     *
     * Catatan aturan data:
     * - status diteruskan/selesai selalu punya bukti dan forwarded_to;
     * - satu laporan diperiksa sengaja tanpa bukti (uji BR-04);
     * - satu laporan baru milik rafli dipertahankan (uji BR-01).
     */
    private array $reports = [
        [
            'username' => 'rafli',
            'area' => 'Kebun Sawit Blok C',
            'title' => 'Titik api di tepi kebun sawit',
            'description' => 'Terlihat kepulan asap dan bara di tepi blok C sejak sore. Angin bertiup ke arah pemukiman.',
            'incident_at' => '-2 days 16:20',
            'address' => 'Jl. Poros Desa Blok C, KM 4',
            'latitude' => -2.97610000,
            'longitude' => 104.73210000,
            'status' => 'baru',
        ],
        [
            'username' => 'rafli',
            'area' => 'Hutan Lindung Sungai Aur',
            'title' => 'Asap tebal di bibir sungai',
            'description' => 'Asap menutup akses perahu warga. Belum terlihat api besar, tetapi bau terbakar kuat.',
            'incident_at' => '-3 days 09:45',
            'address' => 'Tepi Sungai Aur, Dusun II',
            'latitude' => -3.01120000,
            'longitude' => 104.70150000,
            'status' => 'baru',
        ],
        [
            'username' => 'rafli',
            'area' => 'Lahan Gambut Desa Tanjung',
            'title' => 'Lahan gambut mengeluarkan asap',
            'description' => 'Asap keluar dari tanah gambut kering di belakang balai desa. Warga sudah mencoba menyiram dengan air sumur.',
            'incident_at' => '-5 days 13:10',
            'address' => 'Belakang Balai Desa Tanjung',
            'latitude' => -3.04500000,
            'longitude' => 104.68800000,
            'status' => 'diperiksa',
            'forwarded_to' => null,
        ],
        [
            'username' => 'fauzan',
            'area' => 'Perkebunan Karet Km 12',
            'title' => 'Kebakaran semak di pinggir kebun karet',
            'description' => 'Api menjalar di semak kering sepanjang parit kebun karet. Belum mendekati tanaman utama.',
            'incident_at' => '-6 days 11:30',
            'address' => 'Jl. Kebun Karet KM 12',
            'latitude' => -2.95400000,
            'longitude' => 104.75100000,
            'status' => 'diperiksa',
            'forwarded_to' => null,
        ],
        [
            'username' => 'rafli',
            'area' => 'Semak Belukar Jalan Lintas',
            'title' => 'Api di semak belukar jalan lintas',
            'description' => 'Api terlihat dari jalan lintas dan mulai menutup pandangan pengendara.',
            'incident_at' => '-8 days 15:05',
            'address' => 'Jalan Lintas Sumatera, KM 38',
            'latitude' => -3.02150000,
            'longitude' => 104.77600000,
            'status' => 'diteruskan',
            'forwarded_to' => 'BPBD Kabupaten',
            'forwarding_note' => 'Sudah dikoordinasikan dengan pos BPBD terdekat untuk pengecekan lokasi.',
            'evidence' => [
                'file_path' => 'evidence/dummy-1.jpg',
                'original_name' => 'semak-jalan-lintas.jpg',
                'mime_type' => 'image/jpeg',
            ],
        ],
        [
            'username' => 'rafli',
            'area' => 'Kebun Sawit Blok C',
            'title' => 'Bara api di parit blok C',
            'description' => 'Bara masih menyala di dasar parit kering. Petugas desa meminta bantuan pemadaman.',
            'incident_at' => '-10 days 10:15',
            'address' => 'Parit Blok C, RT 04',
            'latitude' => -2.97440000,
            'longitude' => 104.73500000,
            'status' => 'diteruskan',
            'forwarded_to' => 'Manggala Agni Daerah Operasi Sumsel',
            'forwarding_note' => 'Tim Manggala Agni dijadwalkan turun ke lokasi pagi berikutnya.',
            'evidence' => [
                'file_path' => 'evidence/dummy-2.png',
                'original_name' => 'parit-blok-c.png',
                'mime_type' => 'image/png',
            ],
        ],
        [
            'username' => 'fauzan',
            'area' => 'Lahan Gambut Desa Tanjung',
            'title' => 'Gambut terbakar di lahan warga',
            'description' => 'Api menyala di bawah permukaan gambut. Butuh penyiraman berulang agar tidak muncul lagi.',
            'incident_at' => '-12 days 14:40',
            'address' => 'Lahan warga RT 02, Desa Tanjung',
            'latitude' => -3.04720000,
            'longitude' => 104.69050000,
            'status' => 'selesai',
            'forwarded_to' => 'BPBD Kabupaten',
            'forwarding_note' => 'Pemadaman selesai, lahan sudah dibasahi dan dipantau dua hari.',
            'evidence' => [
                'file_path' => 'evidence/dummy-3.pdf',
                'original_name' => 'berita-acara-gambut.pdf',
                'mime_type' => 'application/pdf',
            ],
        ],
        [
            'username' => 'fauzan',
            'area' => 'Hutan Lindung Sungai Aur',
            'title' => 'Bekas terbakar di tepi hutan lindung',
            'description' => 'Area bekas terbakar ditemukan saat patroli. Api sudah padam, tinggal pendinginan.',
            'incident_at' => '-15 days 08:30',
            'address' => 'Patok Hutan Lindung, Blok Aur',
            'latitude' => -3.00910000,
            'longitude' => 104.70420000,
            'status' => 'selesai',
            'forwarded_to' => 'Manggala Agni Daerah Operasi Sumsel',
            'forwarding_note' => 'Patroli lanjutan memastikan tidak ada titik panas tersisa.',
            'evidence' => [
                'file_path' => 'evidence/dummy-4.jpg',
                'original_name' => 'bekas-terbakar-aur.jpg',
                'mime_type' => 'image/jpeg',
            ],
        ],
        [
            'username' => 'rafli',
            'area' => 'Perkebunan Karet Km 12',
            'title' => 'Asap tipis dari tumpukan pelepah',
            'description' => 'Tumpukan pelepah kering berasap. Kemungkinan sisa pembakaran lahan kecil.',
            'incident_at' => '-18 days 17:05',
            'address' => 'Patok kebun karet KM 12, RT 09',
            'latitude' => -2.95630000,
            'longitude' => 104.74880000,
            'status' => 'baru',
        ],
        [
            'username' => 'rafli',
            'area' => 'Semak Belukar Jalan Lintas',
            'title' => 'Semak terbakar dekat gardu listrik',
            'description' => 'Api kecil di semak dekat gardu listrik. Petugas sudah meminta warga menjauh.',
            'incident_at' => '-20 days 12:00',
            'address' => 'Jalan Lintas Sumatera, dekat gardu listrik',
            'latitude' => -3.02500000,
            'longitude' => 104.77850000,
            'status' => 'selesai',
            'forwarded_to' => 'BPBD Kabupaten',
            'forwarding_note' => 'Api dipadamkan petugas bersama warga, gardu listrik aman.',
            'evidence' => [
                'file_path' => 'evidence/dummy-5.jpg',
                'original_name' => 'semak-gardu.jpg',
                'mime_type' => 'image/jpeg',
            ],
        ],
        [
            'username' => 'fauzan',
            'area' => 'Kebun Sawit Blok C',
            'title' => 'Kebakaran kecil di gudang pupuk',
            'description' => 'Kebakaran kecil di tumpukan karung pupuk dekat gudang. Api cepat dipadamkan.',
            'incident_at' => '-22 days 07:50',
            'address' => 'Gudang pupuk Blok C',
            'latitude' => -2.97890000,
            'longitude' => 104.73060000,
            'status' => 'diperiksa',
            'forwarded_to' => null,
            'evidence' => [
                'file_path' => 'evidence/dummy-6.jpg',
                'original_name' => 'gudang-pupuk.jpg',
                'mime_type' => 'image/jpeg',
            ],
        ],
    ];

    public function run(): void
    {
        $users = User::whereIn('username', ['rafli', 'fauzan', 'miqdar'])->get()->keyBy('username');
        $areas = Area::all()->keyBy('name');

        foreach ($this->reports as $data) {
            $report = Report::create([
                'user_id' => $users[$data['username']]->id,
                'area_id' => $areas[$data['area']]->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'incident_at' => now()->modify($data['incident_at']),
                'address' => $data['address'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'status' => $data['status'],
                'forwarded_to' => $data['forwarded_to'] ?? null,
                'forwarding_note' => $data['forwarding_note'] ?? null,
            ]);

            if (isset($data['evidence'])) {
                $evidence = $data['evidence'];
                $evidence['file_size'] = $this->writeEvidenceFile($evidence['file_path'], $evidence['mime_type']);

                Evidence::create([
                    'report_id' => $report->id,
                    ...$evidence,
                ]);
            }
        }
    }

    /**
     * Tulis berkas bukti contoh ke disk local agar tautan unduh tidak menunjuk berkas hilang.
     */
    private function writeEvidenceFile(string $path, string $mimeType): int
    {
        $contents = $this->evidenceContents($mimeType);

        Storage::disk('local')->put($path, $contents);

        return strlen($contents);
    }

    private function evidenceContents(string $mimeType): string
    {
        return match ($mimeType) {
            'application/pdf' => $this->placeholderPdf(),
            'image/png' => base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='),
            default => "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00\xFF\xD9",
        };
    }

    private function placeholderPdf(): string
    {
        $stream = "0.04 0.60 0.41 rg 20 20 160 160 re f\n";

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 200 200] /Contents 4 0 R >>',
            '<< /Length '.strlen($stream)." >>\nstream\n".$stream.'endstream',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n".$object."\nendobj\n";
        }

        $size = count($objects) + 1;
        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".$size."\n0000000000 65535 f \n";

        foreach ($offsets as $offset) {
            $pdf .= str_pad((string) $offset, 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }

        return $pdf.'trailer'."\n<< /Size ".$size." /Root 1 0 R >>\nstartxref\n".$xrefOffset."\n%%EOF\n";
    }
}
