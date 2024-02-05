<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::unprepared(
            file_get_contents(database_path('seeders/sql/referensi.sql'))
        );

        DB::unprepared(
            file_get_contents(database_path('seeders/sql/wilayah.sql'))
        );
    }
}
