<?php

namespace App\Exceptions;

use Exception;

class InsufficientCustomerMoneyException extends Exception
{
    public function __construct($message = "Customer's money is insufficient for the transaction.")
    {
        $this->message = $message;
    }
}
