<?php

namespace Database\Seeders;

use App\Models\Rw;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RwRtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $jumlahRtPerRw = [
        1 => 5,
        2 => 5,
        3 => 6,
        4 => 6,
        5 => 4,
        6 => 2,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 2,
    ];

    foreach ($jumlahRtPerRw as $rwNomor => $jumlahRt) {
        // Buat RW
        $rw = Rw::create([
            'number' => 'RW 0' . $rwNomor,
        ]);

        // Buat RT untuk RW tersebut
        for ($i = 1; $i <= $jumlahRt; $i++) {
            $rw->rts()->create([
                'number' => 'RT 0' . $i,
            ]);
        }
    }
    }
}