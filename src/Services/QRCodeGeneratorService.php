<?php

namespace Farhanisty\DonateloBackend\Services;

interface QRCodeGeneratorService
{
    public function getPath(): string;
    public function create(string $payload): string;
}
