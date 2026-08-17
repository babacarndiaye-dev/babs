<?php

use App\Livewire\Student\Attendances;
use App\Livewire\Student\Dashboard;
use App\Livewire\Student\Grades;
use App\Livewire\Student\MyFormation;
use App\Livewire\Student\Payments;
use App\Livewire\Student\Profile;
use App\Livewire\Student\ScheduleView;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');

Route::get('/profil', Profile::class)->name('profile');
Route::get('/formation', MyFormation::class)->name('formation');
Route::get('/emploi-du-temps', ScheduleView::class)->name('schedule');
Route::get('/notes', Grades::class)->name('grades');
Route::get('/absences', Attendances::class)->name('attendances');
Route::get('/paiements', Payments::class)->name('payments');
Route::get('/documents', fn () => view('student.placeholder', ['title' => 'Mes documents', 'phase' => 'Phase 15']))->name('documents');
