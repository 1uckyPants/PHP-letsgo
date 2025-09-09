<?php

declare(strict_types = 1);

namespace App\Models;

use PDO;
use App\App;
use App\DB;
use App\Service\UploadService;
use App\Models\Model;
use App\View;
use App\Exceptions\DuplicateException;

class UpLoadModel extends Model
{
    public array $transactions = [];

    function getTransactionFiles(): array
    {
        $files = [];

        foreach (scandir(STORAGE_PATH) as $file) {
            if (is_dir($file)) {
                continue;
            }

            $files[] = STORAGE_PATH. $file;
        }
        return $files;
    }

    function getTransactions(string $fileName, ?callable $transactionHandler = null): array
    {

        if (! file_exists($fileName)) {
            trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR);
        }

        $file = fopen($fileName, 'r');

        fgetcsv($file);

        while (($transaction = fgetcsv($file)) !== false) {
            if ($transactionHandler !== null) {
                $transaction = $transactionHandler($transaction);
            }

            $this->transactions[] = ($transaction);
        }

        return $this->transactions;
    }

    public function extractTransaction (array $transactionRow): array
    {
        [$date, $checkNumber, $description, $amount] = $transactionRow;

        $dateObj = \DateTime::createFromFormat('m/d/Y', $date);

        $date = $dateObj ? $dateObj->format('Y-m-d') : null;

        $amount = (float) str_replace([',', '$'], '', $amount);

        return [
            'date' => $date,
            'checkNumber' => $checkNumber,
            'description' => $description,
            'amount' => $amount
        ];
    }

    public function saveTransaction(array $transaction): bool
    {
        try {
            $stmt = $this->db->prepare(
                "
        INSERT INTO transactions (date_, check_, description_, amount) 
        VALUES (:date, :checkNumber, :description, :amount)
    "
            );

            return $stmt->execute([
                ':date' => $transaction['date'],
                ':checkNumber' => $transaction['checkNumber'],
                ':description' => $transaction['description'],
                ':amount' => $transaction['amount'],
            ]);
        }  catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                echo $this->getTransaction();

                exit;
            } else {
            throw $e; }
        }
    }

    function calculateTotals (array $transactions): array
    {
        $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

        foreach ($transactions as $transaction) {
            $totals['netTotal'] += $transaction['amount'];

            if ($transaction['amount'] >= 0) {
                $totals['totalIncome'] += $transaction['amount'];
            } else {
                $totals['totalExpense'] += $transaction['amount'];
            }
        }

        return $totals;
    }

    public function getTransaction()
    {
        $fetchStmt = $this->db->prepare(
            'SELECT date_, check_, description_, amount
            FROM transactions'
        );

        $fetchStmt->execute();

        $transactions = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);

        $totals = $this->calculateTotals($transactions);

        return View::make('viewTransaction', [
            'transactions' => $transactions,
            'totals' => $totals
        ]);
    }



}