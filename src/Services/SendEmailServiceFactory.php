<?php

namespace Farhanisty\DonateloBackend\Services;

class SendEmailServiceFactory
{
    public static function getInstance(): SendEmailService
    {
        return new SendEmailServiceImpl();
    }
}
