<?php

namespace Farhanisty\DonateloBackend\Services;

class OrderPayloadServiceImpl implements OrderPayloadService
{
    public function __construct(
        private string $purchaseDate,
        private float $paymentAmount,
        private string $name,
        private string $email,
        private array $orders
    ) {}

    public function generate(): array
    {
        return [
            'jti' => uniqid('', true),
            'iss' => 'https://api.donatelo.com',
            'iat' => time(),
            'data' => [
                'purchase_date' => $this->purchaseDate,
                'payment_amount' => $this->paymentAmount,
                'customer' => [
                    'name' => $this->name,
                    'email' => $this->email
                ],
                'orders' => $this->orders
            ]
        ];
    }
}
