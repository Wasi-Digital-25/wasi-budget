<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin','staff','viewer']);
    }

    public function view(User $user, Client $client): bool
    {
        return $user->company_id === $client->company_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin','staff']);
    }

    public function update(User $user, Client $client): bool
    {
        return $this->create($user) && $this->view($user, $client);
    }

    public function delete(User $user, Client $client): bool
    {
        return $this->update($user, $client);
    }
}
