<?php

declare(strict_types=1);

namespace App\Service;

class PaymentGatewayService
{
    public function charge(array $customer, float $amount, float $tax)
    {
        sleep(1);

        return (bool) mt_rand(0, 1);
    }
}