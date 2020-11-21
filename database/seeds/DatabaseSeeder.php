<?php

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
        $this->call(PersonaSeeder::class);
        $this->call(PublicacionSeeder::class);
        $this->call(ComentarioSeeder::class);
        $this->call(UserSeeder::class);
    }
}
