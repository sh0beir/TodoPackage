<?php

namespace sh0beir\todo\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function author()
    {
        return $this->morphTo();
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, TaskLabel::class, 'task_id', 'label_id');
    }
}
