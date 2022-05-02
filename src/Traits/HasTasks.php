<?php

namespace sh0beir\todo\Traits;

use sh0beir\todo\Models\Task;

trait HasTasks
{
    public function tasks()
    {
        return $this->morphMany(Task::class, 'author');
    }
}
