<?php
namespace App\Services\Payment;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    public function create_payment($data);

    public function response_payment(Request $request);

    public function cancel_payment(Request $request);
}