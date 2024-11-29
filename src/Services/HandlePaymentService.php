<?php

namespace Farhanisty\DonateloBackend\Services;

use Farhanisty\DonateloBackend\Repositories\MenuRepository;
use Farhanisty\DonateloBackend\Repositories\TokenRepository;
use Farhanisty\Vetran\Facades\Response;
use Farhanisty\Vetran\Application;
use Firebase\JWT\JWT;

class HandlePaymentService
{
    private SendEmailService $sendEmailService;
    private QRCodeGeneratorService $qrGenerator;
    private Application $app;
    private MenuRepository $menuRepository;
    private TokenRepository $tokenRepository;

    public function __construct(Application $app, MenuRepository $menuRepository, TokenRepository $tokenRepository)
    {
        $this->app = $app;
        $this->menuRepository = $menuRepository;
        $this->sendEmailService = SendEmailServiceFactory::getInstance();
        $this->qrGenerator = QRCodeGeneratorServiceFactory::getInstance();
        $this->tokenRepository = $tokenRepository;
    }

    public function handle()
    {
        $inputUser = $this->app->getRoute()->input()->jsonBody();

        $purchaseDate = $this->getCurrentDateTime();
        $username = $inputUser['name'];
        $email = $inputUser['email'];
        $totalPrice = $this->countTotalPrice($inputUser['orders']);

        $mappedPayloadOrder = $this->mapOrderPayloadFormat($inputUser['orders']);

        $payloadGenerator = new OrderPayloadServiceImpl(
            $purchaseDate,
            $totalPrice,
            $username,
            $email,
            $mappedPayloadOrder
        );

        $encodedJwt = JWT::encode($payloadGenerator->generate(), $_ENV['JWT_SECRET_KEY'], 'HS256');

        $this->tokenRepository->create($encodedJwt);

        $imageName = $this->qrGenerator->create($encodedJwt);

        $emailBody = new EmailBodyServiceImpl(
            $username,
            $purchaseDate,
            'Rp ' . $this->convertRupiahNumber($totalPrice),
            $_ENV['WHATSAPP_NUMBER'],
            $encodedJwt
        );

        $imagePath = $this->qrGenerator->getPath() . $imageName;

        $send_status = $this->sendEmailService->send($email, $emailBody->render(), $imagePath);

        $response = $this->app->getResponse();
        $response->responseJson();

        if ($send_status) {
            $response
                ->setStatus(Response::OK)
                ->setBody([
                    'status' => true,
                    'message' => 'OK'
                ])
                ->build();
        } else {
            $response
                ->setStatus(Response::INTERNAL_SERVER_ERROR)
                ->setBody([
                    'status' => false,
                    'message' => 'INTERNAL SERVER ERROR'
                ])
                ->build();
        }
    }

    private function mapOrderPayloadFormat(array $orders): array
    {
        return array_map(function ($order) {
            return [
                'id' => $order['id'],
                'quantity' => $order['qty']
            ];
        }, $orders);
    }

    private function countTotalPrice(array $orders): float
    {
        $menus = $this->getMenus($orders);
        $totalPrice = 0;

        foreach ($menus as $key => $menu) {
            $totalPrice += $menu->price * $orders[$key]['qty'];
        }

        return $totalPrice;
    }

    private function getMenus(array $orders)
    {
        $ids = $this->getOrderIds($orders);
        return $this->menuRepository->getWhereIdIn($ids);
    }

    private function getOrderIds($orders): array
    {
        return array_map(function ($item) {
            return $item['id'];
        }, $orders);
    }

    private function getCurrentDateTime(): string
    {
        date_default_timezone_set('Asia/Makassar');
        return date('Y-m-d H:i:s');
    }

    private function convertRupiahNumber(float $number): string
    {
        return number_format($number, 2, ',', '.');
    }
}
