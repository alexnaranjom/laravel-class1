<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone'];

    /**
     * Retrieve all contacts ordered by the most recently created first.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function allOrderedByLatest()
    {
        return static::latest()->get();
    }
}
