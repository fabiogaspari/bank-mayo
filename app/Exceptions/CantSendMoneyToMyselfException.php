<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class CantSendMoneyToMyselfException extends Exception
{
    public function status(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
