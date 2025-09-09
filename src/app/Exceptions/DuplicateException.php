<?php

declare(strict_types = 1);

namespace App\Exceptions;

class DuplicateException extends \Exception
{
    protected $message = 'Duplicate Error';
}
