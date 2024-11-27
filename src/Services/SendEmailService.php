<?php

namespace Farhanisty\DonateloBackend\Services;

interface SendEmailService
{
    public function send(string $address, string $body, string $imagePath): bool;
}
