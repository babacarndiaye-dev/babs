<?php

use App\Livewire\Auth\Login;
use App\Livewire\Public\Admissions\Start as AdmissionsStart;
use App\Livewire\Public\Admissions\Track as AdmissionsTrack;
use App\Livewire\Public\Formations\Index as FormationsIndex;
use App\Livewire\Public\Formations\Show as FormationsShow;
use App\Livewire\Public\Home;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::get('/etablissement', fn () => view('public.placeholder', ['title' => "L'Établissement", 'phase' => 'Phase 16']))->name('about');

Route::get('/formations', FormationsIndex::class)->name('formations.index');
Route::get('/formations/{slug}', FormationsShow::class)->name('formations.show');

Route::get('/admissions', fn () => view('public.placeholder', ['title' => 'Admissions', 'phase' => 'Phase 16']))->name('admissions');
Route::get('/admissions/candidater', AdmissionsStart::class)->name('admissions.apply');
Route::get('/admissions/suivi', AdmissionsTrack::class)->name('admissions.track');

Route::get('/actualites', fn () => view('public.placeholder', ['title' => 'Actualités', 'phase' => 'Phase 16']))->name('news.index');
Route::get('/galerie', fn () => view('public.placeholder', ['title' => 'Galerie', 'phase' => 'Phase 16']))->name('gallery');
Route::get('/contact', fn () => view('public.placeholder', ['title' => 'Contact', 'phase' => 'Phase 16']))->name('contact');
Route::get('/verification-document', fn () => view('public.placeholder', ['title' => 'Vérification de document', 'phase' => 'Phase 15']))->name('documents.verify');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', Login::class)->name('login');
});

Route::post('/deconnexion', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:super-admin|directeur|administrateur|responsable-academique|scolarite|comptable'])
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));

Route::middleware(['auth', 'role:enseignant'])
    ->prefix('enseignant')
    ->name('teacher.')
    ->group(base_path('routes/teacher.php'));

Route::middleware(['auth', 'role:etudiant'])
    ->prefix('etudiant')
    ->name('student.')
    ->group(base_path('routes/student.php'));

Route::middleware(['auth', 'role:candidat'])
    ->prefix('candidat')
    ->name('candidate.')
    ->group(base_path('routes/candidate.php'));
