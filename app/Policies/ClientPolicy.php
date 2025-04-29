<?php

namespace App\Policies;

use App\Models\User;
use Laravel\Passport\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the client.
     *
     * @param  \App\Models\User  $user
     * @param  \Laravel\Passport\Client  $client
     * @return bool
     */
    public function view(User $user, Client $client)
    {
        return $user->id === $client->user_id;
    }

    /**
     * Determine whether the user can update the client.
     *
     * @param  \App\Models\User  $user
     * @param  \Laravel\Passport\Client  $client
     * @return bool
     */
    public function update(User $user, Client $client)
    {
        return $user->id === $client->user_id;
    }

    /**
     * Determine whether the user can delete the client.
     *
     * @param  \App\Models\User  $user
     * @param  \Laravel\Passport\Client  $client
     * @return bool
     */
    public function delete(User $user, Client $client)
    {
        return $user->id === $client->user_id;
    }
} 