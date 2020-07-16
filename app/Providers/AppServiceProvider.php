<?php

namespace App\Providers;

use App\Mail\UserConfirmEmail;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::created(function ($user) {
            $this->sendMail($user);
        });

        User::updated(function ($user) {
            if ($user->isDirty('email')) {
                $this->sendMail($user);
            }
        });

        Product::updated(function (Product $product) {
            if ($product->quantity == 0 && $product->is_Available()) {
                $product->status = Product::UNAVAILABLE_PRODUCT;
                $product->save();
            }
        });
    }

    private function sendMail(User $user)
    {
        Mail::to($user)->send(new UserConfirmEmail($user));
    }
}
