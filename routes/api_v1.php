<?php

use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use App\Http\Controllers\Api\V1\TicketAuthorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::put('/tickets/{ticket}', [TicketController::class, 'replace'])->name('tickets.replace');
    Route::patch('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    Route::get('/tickets/{ticket}/author', [TicketAuthorController::class, 'show'])->name('tickets.author.show');
    Route::put('/tickets/{ticket}/author', [TicketAuthorController::class, 'replace'])->name('tickets.author.replace');
    Route::patch('/tickets/{ticket}/author', [TicketAuthorController::class, 'update'])->name('tickets.author.update');
    Route::delete('/tickets/{ticket}/author', [TicketAuthorController::class, 'destroy'])->name('tickets.author.destroy');
    
    Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
    Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store');
    Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');
    Route::put('/authors/{author}', [AuthorController::class, 'replace'])->name('authors.replace');
    Route::patch('/authors/{author}', [AuthorController::class, 'update'])->name('authors.update');
    Route::delete('/authors/{author}', [AuthorController::class, 'destroy'])->name('authors.destroy');

    Route::get('/authors/{author}/tickets', [AuthorTicketsController::class, 'index'])->name('authors.tickets.index');
    Route::post('/authors/{author}/tickets', [AuthorTicketsController::class, 'store'])->name('authors.tickets.store');
    Route::get('/authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'show'])->name('authors.tickets.show');
    Route::put('/authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'replace'])->name('authors.tickets.replace');
    Route::patch('/authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'update'])->name('authors.tickets.update');
    Route::delete('/authors/{author}/tickets/{ticket}', [AuthorTicketsController::class, 'destroy'])->name('authors.tickets.destroy');

});
