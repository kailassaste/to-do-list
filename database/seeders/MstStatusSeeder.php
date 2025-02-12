<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MstStatus;

class MstStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('mst_status')->insert([
            ['name' => 'Pending'],
            ['name' => 'In-Progress'],
            ['name' => 'Completed'],
            ['name' => 'Hold'],
        ]);
    }
}
