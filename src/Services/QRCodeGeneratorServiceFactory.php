<?php

namespace Farhanisty\DonateloBackend\Services;

class QRCodeGeneratorServiceFactory
{
    public static function getInstance(): QRCodeGeneratorService
    {
        return new QRCodeGeneratorServiceImpl();
    }
}
