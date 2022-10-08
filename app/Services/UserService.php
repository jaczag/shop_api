<?php

namespace App\Services;

use App\Enums\UserRoleEnum;
use App\Models\User;

/**
 * @property  $user
 */
class UserService
{
    private ?User $user;

    /**
     * @param User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user ?? new User();
    }

    /**
     * @param array $data
     * @return $this
     */
    public function assignData(array $data): static
    {
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        $this->user->role = UserRoleEnum::User;

        return $this;
    }

    /**
     * @return $this
     */
    public function saveUser(): static
    {
        $this->user->save();
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
