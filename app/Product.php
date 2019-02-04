<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'link'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}
