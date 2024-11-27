<?php

namespace Farhanisty\DonateloBackend\Services;

use chillerlan\QRCode\QRCode;

class QRCodeGeneratorServiceImpl implements QRCodeGeneratorService
{
    public const PATH = __DIR__ . '/../storage/qr/';

    public function getPath(): string
    {
        return self::PATH;
    }

    public function create(string $payload): string
    {
        $filename = md5(microtime(true)) . '.svg';
        $qrCode = new QRCode();

        $qrCode->render($payload, self::PATH . $filename);

        return $filename;
    }
}
