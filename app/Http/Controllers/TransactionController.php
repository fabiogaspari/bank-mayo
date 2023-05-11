<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Contracts\CommonController;
use App\Http\Repositories\TransactionRepository;
use App\Http\Services\TransactionService;
use App\Models\Transaction;
use App\Rules\VerifyValidCpfCnpj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends CommonController
{
    public function __construct(
        private TransactionService $service,
        private TransactionRepository $repository,
        private Transaction $model
    ) {
        $this->classname = 'Transaction';
        parent::__construct($service, $repository, $model);
    }

    public function validate(Request $req): \Illuminate\Validation\Validator
    {
        return Validator::make($req->all(), [
            'cpf_cnpj' => ['required',new VerifyValidCpfCnpj,'exists:users,cpf_cnpj'],
            'money' => 'required|numeric|min:0.01'
        ],
        [
            'cpf_cnpj.exists' => 'Conta destino inexistente.',
            'cpf_cnpj.VerifyValidCpfCnpj' => 'Por favor, insira um CPF/CNPJ válido.',
            'money.required' => 'Por favor, insira um valor.',
            'money.min' => 'O valor mínimo para transferência é R$ 0,01'
        ]);
    }
    
    public function allByUserFrom(Request $req)
    {
        return json(fn() => $this->service->allByUserFrom($req));
    }
}
