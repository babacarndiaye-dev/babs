<?php

use App\Livewire\Admin\Applications\Index as ApplicationsIndex;
use App\Livewire\Admin\Applications\Show as ApplicationsShow;
use App\Livewire\Admin\Classes\Form as ClassForm;
use App\Livewire\Admin\Classes\Index as ClassesIndex;
use App\Livewire\Admin\Classes\Show as ClassesShow;
use App\Livewire\Admin\Communication\Events\Form as EventForm;
use App\Livewire\Admin\Communication\Events\Index as EventsIndex;
use App\Livewire\Admin\Communication\Gallery\Index as GalleryIndex;
use App\Livewire\Admin\Communication\News\Form as NewsForm;
use App\Livewire\Admin\Communication\News\Index as NewsIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Documents\Index as DocumentsIndex;
use App\Livewire\Admin\Finance\FeeTypes\Index as FeeTypesIndex;
use App\Livewire\Admin\Finance\Invoices\Form as InvoiceForm;
use App\Livewire\Admin\Finance\Invoices\Index as InvoicesIndex;
use App\Livewire\Admin\Finance\Invoices\Show as InvoicesShow;
use App\Livewire\Admin\Formations\Form as FormationForm;
use App\Livewire\Admin\Formations\Index as FormationsIndex;
use App\Livewire\Admin\ReportCards\Index as ReportCardsIndex;
use App\Livewire\Admin\Settings\Index as SettingsIndex;
use App\Livewire\Admin\Students\Form as StudentForm;
use App\Livewire\Admin\Students\Index as StudentsIndex;
use App\Livewire\Admin\Students\Show as StudentsShow;
use App\Livewire\Admin\Subjects\Index as SubjectsIndex;
use App\Livewire\Admin\Teachers\Form as TeacherForm;
use App\Livewire\Admin\Teachers\Index as TeachersIndex;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\News;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/parametres', SettingsIndex::class)->name('settings.index');

Route::get('/candidatures', ApplicationsIndex::class)->name('applications.index');
Route::get('/candidatures/{application}', ApplicationsShow::class)->name('applications.show');

Route::get('/etudiants', StudentsIndex::class)->name('students.index');
Route::get('/etudiants/creer', StudentForm::class)->name('students.create');
Route::get('/etudiants/{student}', StudentsShow::class)->name('students.show');
Route::get('/etudiants/{student}/modifier', StudentForm::class)->name('students.edit');

Route::get('/enseignants', TeachersIndex::class)->name('teachers.index');
Route::get('/enseignants/creer', TeacherForm::class)->name('teachers.create');
Route::get('/enseignants/{teacher}/modifier', TeacherForm::class)->name('teachers.edit');

Route::get('/formations', FormationsIndex::class)->name('formations.index');
Route::get('/formations/creer', FormationForm::class)->name('formations.create');
Route::get('/formations/{formation}/modifier', FormationForm::class)->name('formations.edit');

Route::get('/matieres', SubjectsIndex::class)->name('subjects.index');

Route::get('/classes', ClassesIndex::class)->name('classes.index');
Route::get('/classes/creer', ClassForm::class)->name('classes.create');
Route::get('/classes/{class}', ClassesShow::class)->name('classes.show');
Route::get('/classes/{class}/modifier', ClassForm::class)->name('classes.edit');

Route::get('/bulletins', ReportCardsIndex::class)->name('report-cards.index');

Route::get('/finance', InvoicesIndex::class)->name('finance.index');
Route::get('/finance/factures/creer', InvoiceForm::class)->name('finance.invoices.create');
Route::get('/finance/factures/{invoice}', InvoicesShow::class)->name('finance.invoices.show');
Route::get('/finance/types-de-frais', FeeTypesIndex::class)->name('finance.fee-types.index');

Route::get('/documents', DocumentsIndex::class)->name('documents.index');

Route::get('/communication', function () {
    return view('admin.communication.hub', [
        'newsCount' => News::count(),
        'eventsCount' => Event::count(),
        'galleryCount' => GalleryItem::count(),
    ]);
})->name('communication.index');

Route::get('/communication/actualites', NewsIndex::class)->name('communication.news.index');
Route::get('/communication/actualites/creer', NewsForm::class)->name('communication.news.create');
Route::get('/communication/actualites/{news}/modifier', NewsForm::class)->name('communication.news.edit');

Route::get('/communication/evenements', EventsIndex::class)->name('communication.events.index');
Route::get('/communication/evenements/creer', EventForm::class)->name('communication.events.create');
Route::get('/communication/evenements/{event}/modifier', EventForm::class)->name('communication.events.edit');

Route::get('/communication/galerie', GalleryIndex::class)->name('communication.gallery.index');
Route::get('/utilisateurs', fn () => view('admin.placeholder', ['title' => 'Utilisateurs & Rôles', 'phase' => 'Phase 5 (extension)']))->name('users.index');
