<?php

use Illuminate\Support\Facades\Route;
use sh0beir\todo\Http\Controllers\Api\LabelController;
use sh0beir\todo\Http\Controllers\Api\TaskController;


Route::group(['prefix' => 'api', 'middleware' => ['auth:api', 'bindings']], function () {

    Route::get('tasks', [TaskController::class, 'index'])->name('api.tasks.index');
    Route::post('tasks', [TaskController::class, 'store'])->name('api.tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('api.tasks.show');
    Route::get('labels/{label}/filter', [TaskController::class, 'filter'])->name('api.tasks.filter');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('api.tasks.update');
    Route::put('tasks/{task}/status', [TaskController::class, 'changeStatus'])->name('api.tasks.changeStatus');
    Route::post('tasks/{task}/attach', [TaskController::class, 'attach'])->name('api.tasks.attach');

    Route::get('labels', [LabelController::class, 'index'])->name('api.labels.index');
    Route::post('labels', [LabelController::class, 'store'])->name('api.labels.store');
    Route::get('labels/{label}', [LabelController::class, 'show'])->name('api.labels.show');
    // Route::put('labels/{label}', [LabelController::class, 'update'])->name('api.labels.update');
});
