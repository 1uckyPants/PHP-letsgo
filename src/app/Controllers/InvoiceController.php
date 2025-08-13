<?php

declare(strict_types=1);

namespace App\Controllers;

class InvoiceController
{
    public function index(): string
    {
        return '';
    }

    public function create(): string
    {
        return '';
    }

    public function store()
    {
        $amount = $_POST['amount'];

        var_dump($amount);
    }
}
