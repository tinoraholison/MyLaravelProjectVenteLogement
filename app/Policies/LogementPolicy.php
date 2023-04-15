<?php

namespace App\Policies;

use App\Models\Logement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(['Admin','User']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logement  $logement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Logement $logement)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole('User');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logement  $logement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Logement $logement)
    {
        return $user->hasRole('User');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logement  $logement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Logement $logement)
    {
        return $user->hasRole('User');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logement  $logement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Logement $logement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logement  $logement
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Logement $logement)
    {
        //
    }
}
