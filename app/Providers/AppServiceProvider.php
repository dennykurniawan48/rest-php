<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Models\Product;
use App\Models\User;
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
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::created(function($user){
            Mail::to($user->email)->send(new UserCreated($user));
        });

        Product::updated(function($product){
            if($product->stock <= 0 && $product->available){
                $product->available = false;
                $product->save();
            }
        });
    }
}
