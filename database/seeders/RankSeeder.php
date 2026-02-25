<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [
            [
                'code' => 'MM',
                'short_name' => 'MASTER MARINER',
                'alias' => 'M. MARINER'
            ],
            [
                'code' => 'CM',
                'short_name' => 'CHIEF MATE',
                'alias' => 'C. MATE'
            ],
            [
                'code' => '2M',
                'short_name' => 'SECOND MATE',
                'alias' => '2. MATE'
            ],
            [
                'code' => '3M',
                'short_name' => 'THIRD MATE',
                'alias' => '3. MATE'
            ],
            [
                'code' => 'CE',
                'short_name' => 'CHIEF ENGINEER',
                'alias' => 'C. ENGINEER'
            ],
            [
                'code' => '1AE',
                'short_name' => 'FIRST ASSISTANT ENGINEER',
                'alias' => '1. ASSISTANT ENGINEER'
            ],
            [
                'code' => '2AE',
                'short_name' => 'SECOND ASSISTANT ENGINEER',
                'alias' => '2. ASSISTANT ENGINEER'
            ],
            [
                'code' => '3AE',
                'short_name' => 'THIRD ASSISTANT ENGINEER',
                'alias' => '3. ASSISTANT ENGINEER'
            ],
            [
                'code' => 'AB',
                'short_name' => 'ABLE SEAMAN',
                'alias' => 'A. SEAMAN'
            ],
            [
                'code' => 'OS',
                'short_name' => 'ORDINARY SEAMAN',
                'alias' => 'O. SEAMAN'
            ],
            [
                'code' => 'WPR',
                'short_name' => 'WIPER',
                'alias' => 'WPER'
            ],
            [
                'code' => 'BS',
                'short_name' => 'BOSUN',
                'alias' => 'BSN'
            ],
            [
                'code' => 'FTR',
                'short_name' => 'FITTER',
                'alias' => 'FITTR'
            ],
            [
                'code' => 'DC',
                'short_name' => 'DECK CADET',
                'alias' => 'D. CADET'
            ],
            [
                'code' => 'EC',
                'short_name' => 'ENGINE CADET',
                'alias' => 'E. CADET'
            ],
            [
                'code' => 'CCK',
                'short_name' => 'CHIEF COOK',
                'alias' => 'C. COOK'
            ],
            [
                'code' => '2CK',
                'short_name' => 'SECOND COOK',
                'alias' => '2. COOK'
            ],
            [
                'code' => 'MSM',
                'short_name' => 'MESSMAN',
                'alias' => 'M. MAN'
            ],
        ];

        Rank::query()->insert($ranks);
    }
}
