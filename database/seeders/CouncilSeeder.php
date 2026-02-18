<?php

namespace Database\Seeders;

use App\Models\Council;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouncilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrCouncil = [
            [
                'councilName' => 'SEMILLAS',
                'comunityId' => 1,
                'googleMaps' => 'http/direccion/inventada/del/estado/zulia/Maracaibo',
                'photoCouncil' => 'No hay'
            ]
        ];  
        Council::insert($arrCouncil);
    }
}
