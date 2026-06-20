<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin1@kafeinarts.com'],
            [
                'name' => 'Admin 1',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin2@kafeinarts.com'],
            [
                'name' => 'Admin 2',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $competitions = [
            ['name' => 'Balap Karung', 'slug' => 'balap-karung', 'type' => 'individu', 'age_category' => 'anak-anak', 'svg_illustration_key' => 'balap-karung'],
            ['name' => 'Tarik Tambang', 'slug' => 'tarik-tambang', 'type' => 'tim', 'age_category' => 'dewasa', 'svg_illustration_key' => 'tarik-tambang'],
            ['name' => 'Panjat Pinang', 'slug' => 'panjat-pinang', 'type' => 'tim', 'age_category' => 'dewasa', 'svg_illustration_key' => 'panjat-pinang'],
            ['name' => 'Makan Kerupuk', 'slug' => 'makan-kerupuk', 'type' => 'individu', 'age_category' => 'anak-anak', 'svg_illustration_key' => 'makan-kerupuk'],
            ['name' => 'Lomba Kelereng', 'slug' => 'lomba-kelereng', 'type' => 'individu', 'age_category' => 'remaja', 'svg_illustration_key' => 'lomba-kelereng'],
            ['name' => 'Balap Bakiak', 'slug' => 'balap-bakiak', 'type' => 'tim', 'age_category' => 'remaja', 'svg_illustration_key' => 'balap-bakiak'],
        ];

        foreach ($competitions as $comp) {
            Competition::firstOrCreate(
                ['slug' => $comp['slug']],
                $comp
            );
        }
    }
}
