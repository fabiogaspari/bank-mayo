<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InsufficientBalanceException extends Exception
{

    public function status(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

}
