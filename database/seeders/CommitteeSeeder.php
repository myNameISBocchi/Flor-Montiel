<?php

namespace Database\Seeders;

use App\Models\Council;
use App\Models\councilCommittees;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrComite = [

        [
            'councilName' => 'SEMILLAS',
            'committes' =>
                [
                [
                    'unitName' => 'ADMINISTRATIVA',
                'committeeName' => 'FUEGO'
                ],
                [
                    'unitName' => 'CONTRALORIA',
                'committeeName' => 'AGUA'
                
                ]
                ],    
        ],

        [
            'councilName' => 'ARBOLES',
            'committes' => 
                [
                [
                    'unitName' => 'EJECUTIVA',
                    'committeeName' => 'ARENA'
                ]
                ]
        ]
        ];

        foreach($arrComite as $key => $data){
            $arrInsert = [];
            $council = Council::where('councilName', '=', $data['councilName'])->first();
            foreach($data['committes'] as $key => $item){
                $unit = Unit::where('unitName', '=', $item['unitName'])->first();
                $arrInsert[] =[
                    'councilId' => $council->id,
                    'unitId' => $unit->id,
                    'committeeName' => $item['committeeName']
                ];
            }
            councilCommittees::insert($arrInsert);
        }
        
    }
}
