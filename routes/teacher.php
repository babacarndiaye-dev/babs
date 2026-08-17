<?php

use App\Livewire\Teacher\Classes\Index as ClassesIndex;
use App\Livewire\Teacher\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');

Route::get('/classes', ClassesIndex::class)->name('classes.index');
Route::get('/emploi-du-temps', fn () => view('teacher.placeholder', ['title' => 'Emploi du temps', 'phase' => 'Phase 11']))->name('schedule');
Route::get('/presences', fn () => view('teacher.placeholder', ['title' => 'Présences', 'phase' => 'Phase 11']))->name('attendances.index');
Route::get('/evaluations', fn () => view('teacher.placeholder', ['title' => 'Évaluations & Notes', 'phase' => 'Phase 12']))->name('assessments.index');
