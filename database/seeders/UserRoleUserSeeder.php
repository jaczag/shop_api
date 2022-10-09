<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleUserSeeder extends Seeder
{
    /**
     * run: php artisan db:seed --class=UserRoleUserSeeder
     * @return void
     */
    public function run(): void
    {
       User::factory()->count(50)->create([
            'role' => UserRoleEnum::User
        ]);
    }
}
