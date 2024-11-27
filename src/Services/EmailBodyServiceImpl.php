<?php

namespace Farhanisty\DonateloBackend\Services;

class EmailBodyServiceImpl implements EmailBodyService
{
    public function __construct(
        private string $customerName,
        private string $purchaseDate,
        private string $paymentAmout,
        private string $whatsappNumber,
        private string $token
    ) {}

    public function render(): string
    {
        return '
    <html>
    <head>
        <title>Thank You for Your Purchase</title>
    </head>
    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; margin: 0; padding: 0;">
        <table align="center" width="600" style="border: 1px solid #ddd; background-color: #fff; padding: 20px; margin-top: 20px; border-radius: 8px;">
            <tr>
                <td>
                    <h2 style="color: #4CAF50; text-align: center;">Thank You for Your Purchase at Donatelo! 🍩</h2>
                    <p>Hello <strong>' . $this->customerName . '</strong>,</p>
                    <p>Thank you for shopping with <strong>Donatelo</strong>! We’re excited to let you know that your order has been received and your payment has been successfully processed. Here are your order details:</p>
                    
                    <table width="100%" style="border-collapse: collapse; margin: 20px 0;">
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Purchase Date:</strong></td>
                            <td style="padding: 8px; border: 1px solid #ddd;">' . $this->purchaseDate . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Total Payment:</strong></td>
                            <td style="padding: 8px; border: 1px solid #ddd;">' . $this->paymentAmout . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Token:</strong></td>
                            <td style="padding: 8px; border: 1px solid #ddd;">' . $this->token . '</td>
                        </tr>
                    </table>

                    <p>To complete your order, please visit the nearest <strong>Donatelo</strong> store and show the <strong>QR Code</strong> attached to this email. The store staff will verify your order and hand over your delicious donuts.</p>

                    <p>If you have any questions or need further assistance, feel free to reach out to our team via this email or WhatsApp at ' . $this->whatsappNumber . '.</p>

                    <p>We truly appreciate your trust in <strong>Donatelo</strong>. We hope our donuts bring sweetness and joy to your day! 😊</p>

                    <p>Warm regards,<br>
                    <strong>The Donatelo Team</strong></p>
                </td>
            </tr>
        </table>
    </body>
    </html>
';
    }
}
