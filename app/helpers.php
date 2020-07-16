<?php

use App\Mail\UserConfirmEmail;
use App\User;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Support\Facades\Mail;

if (! function_exists('auth')) {
    /**
     * Get the available auth instance.
     *
     * @param User $user
     * @param $retries
     * @param $times
     * @return void
     */
    function sendMail(User $user, $retries = 5, $times = 300)
    {
        try {
            retry($retries, function () use ($user) {
                Mail::to($user)->send(new UserConfirmEmail($user));
            }, $times);
        } catch (\Exception $e) {
        }

        return;
    }
}
