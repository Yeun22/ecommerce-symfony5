<?php

namespace App\Stripe;

use App\Entity\Purchase;

class StripeService
{
    protected $secretKey;
    protected $publicKey;

    public function __construct(string $secretKey, string $publicKey)
    {
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    public function getPaymentIntent(Purchase $purchase)
    {
        \Stripe\Stripe::setApiKey($this->secretKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $purchase->getTotal(), //Il faut des centimes et on les stockes en cents donc c'est cool
            'currency' => 'eur'
        ]);
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
