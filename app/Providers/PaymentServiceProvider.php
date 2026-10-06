<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\RazorpayPayment;
use App\Services\Payment\PaypalPayment;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind PaymentGatewayInterface to a specific implementation dynamically
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            
            // Get the request instance
            $request = $app->make('request'); 
    
            $paymentMethod = $request->input('payment_method'); 

            return match ($paymentMethod) {
                'razorpay' => new RazorpayPayment(),
                'paypal' => new PayPalPayment()
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
