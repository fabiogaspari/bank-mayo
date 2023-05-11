<?php

namespace App\Http\Services\Contracts;

use App\Http\Repositories\Contracts\IRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CommonService implements IService
{
    public function __construct(private IRepository $repository, private Model $model)
    {
        $this->repository = new $repository($model);
    }

    public function create(Request $req): Model
    {
        return $this->repository->create($req->toArray());
    }
    
    public function store(Request $req): bool
    {
        return $this->repository->store($req->toArray());
    }
    
    public function index(): LengthAwarePaginator
    {
        return $this->repository->index();
    }
    
    public function show(int $id): Model|null
    {
        return $this->repository->show($id);
    }
    
    public function update(Request $req, int $id): bool
    {
        return $this->repository->update($req->toArray(), $id);
    }
    
    public function destroy(int $id): bool | null
    {
        return $this->repository->destroy($id);
    }
}
