<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, Store $store)
    {
        return ($user->isAdmin() || $user->isSuper()) || ($user->isSeller() && $user->id === $store->user_id);
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Transactions $transactions, Store $store)
    {
        // return ($user->isAdmin() || $user->isSuper()) || ($user->isSeller() && $user->id == $store->user_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Transactions $transactions)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Transactions $transactions)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Transactions $transactions)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Transactions $transactions)
    {
        //
    }
}
