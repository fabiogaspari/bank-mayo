<?php

namespace App\Http\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CommonRepository implements IRepository
{
    public function __construct(private Model $model)
    {
        $this->model = new $model();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function store(array $data)
    {
        $this->model->fill($data);

        return $this->model->store();
    }
    
    public function index(): LengthAwarePaginator
    {
        return ($this->model::class)::paginate();
    }
    
    public function show(int $id): Model|null
    {
        return ($this->model::class)::where('id', $id)->first();
    }
    
    public function update(array $data, int $id): bool
    {
        $model = $this->show($id);
        $model ?? throw new NotFoundResourceException(__('NOT_FOUND_MODEL')); 
        $model->fill($data);

        return $model->update();
    }
    
    public function destroy(int $id): bool | null
    {
        $model = $this->show($id);
        $model ?? throw new NotFoundResourceException(__('NOT_FOUND_MODEL')); 
        
        return $model->delete();
    }

}
