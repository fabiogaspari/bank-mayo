<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Contracts\CommonRepository;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionRepository extends CommonRepository
{
    public function __construct(private Transaction $model)
    {
        parent::__construct($model);
    }

    public function verifyHasBalance(float $value): bool
    {
        return Auth::user()->balance >= $value;
    }

    public function allByUserFrom(Request $req)
    {
        return $this->model->where('from_id', Auth::user()->id)->get();
    }
}
