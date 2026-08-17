<?php

use App\Http\Controllers\DocumentPdfController;
use App\Http\Controllers\ReceiptPdfController;
use App\Http\Controllers\ReportCardPdfController;
use App\Livewire\Auth\Login;
use App\Livewire\Public\Admissions\Start as AdmissionsStart;
use App\Livewire\Public\Admissions\Track as AdmissionsTrack;
use App\Livewire\Public\Documents\Verify as DocumentsVerify;
use App\Livewire\Public\Formations\Index as FormationsIndex;
use App\Livewire\Public\Formations\Show as FormationsShow;
use App\Livewire\Public\Gallery;
use App\Livewire\Public\Home;
use App\Livewire\Public\News\Index as NewsIndex;
use App\Livewire\Public\News\Show as NewsShow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::get('/etablissement', fn () => view('public.placeholder', ['title' => "L'Établissement", 'phase' => 'Phase 16']))->name('about');

Route::get('/formations', FormationsIndex::class)->name('formations.index');
Route::get('/formations/{slug}', FormationsShow::class)->name('formations.show');

Route::get('/admissions', fn () => view('public.placeholder', ['title' => 'Admissions', 'phase' => 'Phase 16']))->name('admissions');
Route::get('/admissions/candidater', AdmissionsStart::class)->name('admissions.apply');
Route::get('/admissions/suivi', AdmissionsTrack::class)->name('admissions.track');

Route::get('/actualites', NewsIndex::class)->name('news.index');
Route::get('/actualites/{slug}', NewsShow::class)->name('news.show');
Route::get('/galerie', Gallery::class)->name('gallery');
Route::get('/contact', fn () => view('public.placeholder', ['title' => 'Contact', 'phase' => 'Phase 16']))->name('contact');
Route::get('/verification-document', DocumentsVerify::class)->name('documents.verify');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', Login::class)->name('login');
});

Route::get('/bulletins/{reportCard}', [ReportCardPdfController::class, 'show'])
    ->middleware('auth')
    ->name('report-cards.pdf');

Route::get('/recus/{payment}', [ReceiptPdfController::class, 'show'])
    ->middleware('auth')
    ->name('receipts.pdf');

Route::get('/documents/{document}', [DocumentPdfController::class, 'show'])
    ->middleware('auth')
    ->name('documents.pdf');

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
