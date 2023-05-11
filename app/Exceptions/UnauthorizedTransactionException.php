<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UnauthorizedTransactionException extends Exception
{
    
    public function status(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }

}
