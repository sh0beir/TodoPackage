<?php

namespace sh0beir\todo\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    //
    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, TaskLabel::class, 'label_id', 'task_id');
    }
}
