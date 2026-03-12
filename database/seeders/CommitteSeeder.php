<?php

namespace Database\Seeders;

use App\Models\Committee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommitteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrCommittee = [
            [
                'committeeName' => 'Contraloria'
            ],
            [
                'committeeName' => 'ADMINISTRATIVA'
            ],
            [
                'committeeName' => 'EJECUTIVA'
            ]

        ];
        Committee::insert($arrCommittee);
    }
}
