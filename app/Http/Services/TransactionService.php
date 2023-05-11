<?php

namespace App\Http\Services;

use App\Events\TransactionProcessed;
use App\Exceptions\CantSendMoneyToMyselfException;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\UnauthorizedTransactionException;
use App\Http\Repositories\TransactionRepository;
use App\Http\Services\Contracts\CommonService;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionService extends CommonService
{
    public function __construct(private TransactionRepository $repository, private Transaction $model)
    {
        parent::__construct($repository, $model);
    }

    public function store(Request $req): bool
    {
        DB::beginTransaction();

        if ( !$this->repository->verifyHasBalance($req->money) ) {
            $number = floor((Auth::user()->balance*100))/100;
            throw new InsufficientBalanceException(
                "Saldo insuficiente. Seu saldo atual é: R$ "
                . number_format($number, 2, ',', '.'));
        }

        $userTo = User::where('cpf_cnpj', $req->cpf_cnpj)->first();
        $userFrom = Auth::user();
        if (Auth::user()->id == $userTo->id) {
            throw new CantSendMoneyToMyselfException("Você não pode enviar dinheiro para sí mesmo.");
        }
        
        $reqCollect = $req->collect();
        $concatenated = $reqCollect->merge([
            'value' => $req->money,
            'from_id' => $userFrom->id,
            'to_id' => $userTo->id
        ]);

        $autorization = Http::get('https://run.mocky.io/v3/f2fe9a2d-090f-4129-b9bf-70d283c97d5c');
        if (data_get($autorization, 'messagem') != 'autorizado') {
            throw new UnauthorizedTransactionException("Essa transação não foi autorizada.");
        }

        $userFrom->balance -= $req->money;
        $userFrom->save();
        $userTo->balance += $req->money;
        $userTo->save();

        $object = $this->repository->create($concatenated->toArray());

        event(new TransactionProcessed($object));

        DB::commit();

        return $object ? true : false;
    }

    public function allByUserFrom(Request $req)
    {
        return $this->repository->allByUserFrom($req);
    }
}
