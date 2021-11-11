<?php

namespace Database\Seeders;

use App\Models\Board;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Board::create(['name' => 'Development', 'short_name' => 'dev', 'bump_limit' => 500]);
    }
}
