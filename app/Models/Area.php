<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class Area extends Model
{
    //
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
