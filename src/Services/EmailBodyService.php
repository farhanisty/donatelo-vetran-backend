<?php

namespace Farhanisty\DonateloBackend\Services;

interface EmailBodyService
{
    public function render(): string;
}
