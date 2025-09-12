<?php

declare(strict_types=1);

namespace App\Service;

class EmailService
{
    public function send(array $to, string $template): bool
    {
        sleep(1);

        return true;
    }
}