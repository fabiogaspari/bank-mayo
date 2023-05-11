<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'value',
        'from_id',
        'to_id',
    ];

    public function from()
    {
        $this->hasOne(User::class, 'id', 'from_id');
    }

    public function to()
    {
        $this->hasOne(User::class, 'id', 'to_id');
    }
}
