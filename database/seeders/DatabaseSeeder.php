<?php

namespace Database\Seeders;

use App\Models\Userfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user with monitorsetup = 'fifo'
        Userfile::updateOrCreate(
            ['usrcde' => 'testfifo'],
            [
                'email' => 'testfifo@system.local',
                'name' => 'testfifo',
                'usrpwd' => sha1('password123'), // SHA1 hashed password
                'monitorsetup' => 'fifo',
            ]
        );

        // Create test user with monitorsetup = 'manual'
        Userfile::updateOrCreate(
            ['usrcde' => 'testmanual'],
            [
                'email' => 'testmanual@system.local',
                'name' => 'testmanual',
                'usrpwd' => sha1('password123'), // SHA1 hashed password
                'monitorsetup' => 'manual',
            ]
        );

    }
}
