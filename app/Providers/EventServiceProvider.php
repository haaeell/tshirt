<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class EventServiceProvider extends ServiceProvider
{
        protected $listen = [
                Login::class => [
                        function ($event) {
                                $user = $event->user;
                                $user->update(['is_online' => true]);
                        }
                ],
                Logout::class => [
                        function ($event) {
                                $user = $event->user;
                                $user->update(['is_online' => false]);
                        }
                ],
        ];
}
