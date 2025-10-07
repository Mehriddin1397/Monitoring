<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompletedWork extends Model
{
    protected $fillable = ['project_name','tech_info','results'];
}
