<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'object_type'];

    public static function forObjectType($type)
    {
        return self::where('object_type', $type)->get();
    }

    public function categoryables()
    {
        return $this->morphedByMany(Task::class, 'categoryable');
    }

    public function documents()
    {
        return $this->morphedByMany(Document::class, 'categoryable');
    }
    public function libraries()
    {
        return $this->morphedByMany(Library::class, 'categoryable');
    }
}
