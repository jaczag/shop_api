<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleAdminSeeder extends Seeder
{
    /**
     * run: php artisan db:seed --class=UserRoleAdminSeeder
     * @return void
     */
    public function run(): void
    {
        User::factory()->count(10)->create([
            'role' => UserRoleEnum::Admin
        ]);
    }
}
