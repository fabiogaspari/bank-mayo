<?php

namespace App\Http\Controllers\Contracts;

use App\Http\Repositories\Contracts\IRepository;
use App\Http\Services\Contracts\IService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

abstract class CommonController implements IController
{
    protected string $classname;
    private const PATH_BASE = '';

    public function __construct(
        private IService $service,
        private IRepository $repository,
        private Model $model
    ){}

    public function index()
    {
        return Inertia::render(CommonController::PATH_BASE.$this->classname.'/Index');
    }

    public function register()
    {
        return Inertia::render(CommonController::PATH_BASE.$this->classname.'/Register');
    }


    public function store(Request $req)
    {
        if ( $redirect = $this->redirectIfIsntValid($req) ) {
            return $redirect;
        }

        return json(fn() => $this->service->store($req));
    }

    public function all()
    {
        return json(fn() => $this->service->index());
    }

    public function show(int $id)
    {
        return json(fn() => $this->service->show($id));
    }

    public function update(Request $req, int $id)
    {
        if ( $redirect = $this->redirectIfIsntValid($req) ) {
            return $redirect;
        }

        return json(fn() => $this->service->update($req, $id));
    }

    public function destroy(int $id)
    {
        return json(fn() => $this->service->destroy($id));
    }

    protected function redirectIfIsntValid(Request $req) {
        $validator = $this->validate($req);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        return null;
    }

    public abstract function validate(Request $req): \Illuminate\Validation\Validator;

}
