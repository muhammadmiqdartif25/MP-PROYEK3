<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('evidences')]
#[Fillable(['report_id', 'file_path', 'original_name', 'mime_type', 'file_size'])]
class Evidence extends Model {}
