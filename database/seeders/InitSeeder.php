<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(
            [
            PrivilegeSeeder::class,
            RoleSeeder::class,
            ComunitieSeeder::class,
            RolePrivilegeSeeder::class,
            CouncilSeeder::class,
            UnitSeeder::class,
            CommitteeSeeder::class
            ]
        );
    }
}
