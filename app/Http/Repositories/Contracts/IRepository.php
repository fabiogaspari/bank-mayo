<?php

namespace App\Http\Repositories\Contracts;

interface IRepository
{
    public function create(array $data);
    public function store(array $data);
    public function index();
    public function show(int $id);
    public function update(array $data, int $id);
    public function destroy(int $id);
}
