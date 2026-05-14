<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagTranslation extends Model
{
    protected $fillable = ['tag_id', 'locale', 'name', 'slug', 'description', 'meta_title', 'meta_description'];

    public $timestamps = false;

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
