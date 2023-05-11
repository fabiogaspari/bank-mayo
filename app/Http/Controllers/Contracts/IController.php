<?php

namespace App\Http\Controllers\Contracts;

use Illuminate\Http\Request;

interface IController
{
    public function store(Request $req);
    public function all();
    public function show(int $id);
    public function update(Request $req, int $id);
    public function destroy(int $id);
}


