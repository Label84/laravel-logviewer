<?php

use Illuminate\Support\Facades\Route;
use Label84\LogViewer\Http\Controllers\LogViewerController;

Route::get('/', [LogViewerController::class, 'index'])->name('logviewer.index');
