<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Service\EmailService;
use App\Service\InvoiceService;
use App\Service\PaymentGatewayService;
use App\Service\SalesTaxService;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
    public function test_it_processes_invoice(): void
    {
        $salesTaxServiceMock = $this->createMock(SalesTaxService::class);
        $EmailServiceMock = $this->createMock(EmailService::class);
        $PaymentGatewayServiceMock = $this->createMock(PaymentGatewayService::class);

        $PaymentGatewayServiceMock->method('charge')->willReturn(true);

        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $PaymentGatewayServiceMock,
            $EmailServiceMock
        );

        $customer = ['name' => 'Gio'];
        $amount = 150;

        $result = $invoiceService->process($customer, $amount);

        $this->assertTrue($result);
    }

    public function test_it_sends_receipt_email_when_invoice_is_processed(): void
    {
        $salesTaxServiceMock = $this->createMock(SalesTaxService::class);
        $EmailServiceMock = $this->createMock(EmailService::class);
        $PaymentGatewayServiceMock = $this->createMock(PaymentGatewayService::class);

        $PaymentGatewayServiceMock->method('charge')->willReturn(true);

        $EmailServiceMock
            ->expects($this->once())
            ->method('send')
            ->with(['name' => 'John'], 'receipt');

        $invoiceService = new InvoiceService(
            $salesTaxServiceMock,
            $PaymentGatewayServiceMock,
            $EmailServiceMock
        );

        $customer = ['name' => 'Gio'];
        $amount = 150;

        $result = $invoiceService->process($customer, $amount);

        $this->assertTrue($result);
    }
}