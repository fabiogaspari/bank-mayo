<?php

namespace App\Http\Services\Contracts;

use Illuminate\Http\Request;

interface IService
{
    public function store(Request $req);
    public function index();
    public function show(int $id);
    public function update(Request $req, int $id);
    public function destroy(int $id);
}
