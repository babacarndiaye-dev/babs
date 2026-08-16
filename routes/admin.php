<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Settings\Index as SettingsIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/parametres', SettingsIndex::class)->name('settings.index');

Route::get('/candidatures', fn () => view('admin.placeholder', ['title' => 'Candidatures', 'phase' => 'Phase 8']))->name('applications.index');
Route::get('/etudiants', fn () => view('admin.placeholder', ['title' => 'Étudiants', 'phase' => 'Phase 9']))->name('students.index');
Route::get('/enseignants', fn () => view('admin.placeholder', ['title' => 'Enseignants', 'phase' => 'Phase 10']))->name('teachers.index');
Route::get('/formations', fn () => view('admin.placeholder', ['title' => 'Gestion des formations', 'phase' => 'Phase 7']))->name('formations.index');
Route::get('/classes', fn () => view('admin.placeholder', ['title' => 'Classes', 'phase' => 'Phase 10']))->name('classes.index');
Route::get('/finance', fn () => view('admin.placeholder', ['title' => 'Finance', 'phase' => 'Phase 14']))->name('finance.index');
Route::get('/communication', fn () => view('admin.placeholder', ['title' => 'Actualités & Galerie', 'phase' => 'Phase 16']))->name('communication.index');
Route::get('/utilisateurs', fn () => view('admin.placeholder', ['title' => 'Utilisateurs & Rôles', 'phase' => 'Phase 5 (extension)']))->name('users.index');
