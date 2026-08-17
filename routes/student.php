<?php

use App\Livewire\Student\Dashboard;
use App\Livewire\Student\MyFormation;
use App\Livewire\Student\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');

Route::get('/profil', Profile::class)->name('profile');
Route::get('/formation', MyFormation::class)->name('formation');
Route::get('/emploi-du-temps', fn () => view('student.placeholder', ['title' => 'Emploi du temps', 'phase' => 'Phase 11']))->name('schedule');
Route::get('/notes', fn () => view('student.placeholder', ['title' => 'Mes notes', 'phase' => 'Phase 12']))->name('grades');
Route::get('/absences', fn () => view('student.placeholder', ['title' => 'Mes absences', 'phase' => 'Phase 11']))->name('attendances');
Route::get('/paiements', fn () => view('student.placeholder', ['title' => 'Mes paiements', 'phase' => 'Phase 14']))->name('payments');
Route::get('/documents', fn () => view('student.placeholder', ['title' => 'Mes documents', 'phase' => 'Phase 15']))->name('documents');
