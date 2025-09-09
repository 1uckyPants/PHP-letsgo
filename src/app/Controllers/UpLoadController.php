<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UpLoadModel;
use App\Service\UploadService;
use App\Models\Model;

/** сделать catch и try если у загруженного файла не будет имени */

class UpLoadController
{
    public function upload(): void
    {
        $uploadservice = new UploadService();
        $uploadservice->upload();

        $model = new UpLoadModel();

        $transactions = [];

        $fileName = $uploadservice->filePath();

        $transactions = array_merge($transactions, $model->getTransactions($fileName, [$model, 'extractTransaction']));

        foreach ($transactions as $transaction) {
            $model->saveTransaction($transaction);
        }

        echo $model->getTransaction();

        header('Location: /viewTransaction');
        exit;
    }

    public function giveTransaction()
    {
       echo (new UpLoadModel())->getTransaction();
    }

    }
