<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;

class QuotePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin','staff','viewer']);
    }

    public function view(User $user, Quote $quote): bool
    {
        return $user->company_id === $quote->company_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin','staff']);
    }

    public function update(User $user, Quote $quote): bool
    {
        return $this->create($user) && $this->view($user, $quote);
    }

    public function delete(User $user, Quote $quote): bool
    {
        return $this->update($user, $quote);
    }

    public function send(User $user, Quote $quote): bool
    {
        return $this->update($user, $quote);
    }

    public function accept(User $user, Quote $quote): bool
    {
        return $this->update($user, $quote);
    }

    public function reject(User $user, Quote $quote): bool
    {
        return $this->update($user, $quote);
    }
}
