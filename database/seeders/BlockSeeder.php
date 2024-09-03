<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Block;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Block::create([
            'user' => '1',
            'block_user' => '2',
        ]);
        Block::create([
            'user' => '1',
            'block_user' => '3',
        ]);
        Block::create([
            'user' => '2',
            'block_user' => '3',
        ]);
    }
}