<?php

    use App\Models\User;
    use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


    Broadcast::channel('App.Models.User.{id}', function (User $user, int $id): bool {
        return (int) $user->getKey() === $id;
    });

    Broadcast::channel('top-navigation.user.{id}', function (User $user, int $id): bool {
        return (int) $user->getKey() === $id;
    });

    Broadcast::channel('top-navigation.role.{role}', function (User $user, string $role): bool {
        return method_exists($user, 'hasRole') && $user->hasRole($role);
    });

