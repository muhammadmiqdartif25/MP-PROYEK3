<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Wilayah dummy untuk pengujian filter dan rekap.
     */
    private array $areas = [
        'Kebun Sawit Blok C',
        'Hutan Lindung Sungai Aur',
        'Lahan Gambut Desa Tanjung',
        'Perkebunan Karet Km 12',
        'Semak Belukar Jalan Lintas',
    ];

    public function run(): void
    {
        foreach ($this->areas as $name) {
            Area::create(['name' => $name]);
        }
    }
}
