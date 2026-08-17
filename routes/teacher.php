<?php

use App\Livewire\Teacher\Assessments\Grades as AssessmentGrades;
use App\Livewire\Teacher\Assessments\Index as AssessmentsIndex;
use App\Livewire\Teacher\Attendances\Index as AttendancesIndex;
use App\Livewire\Teacher\Classes\Index as ClassesIndex;
use App\Livewire\Teacher\Dashboard;
use App\Livewire\Teacher\ScheduleView;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');

Route::get('/classes', ClassesIndex::class)->name('classes.index');
Route::get('/emploi-du-temps', ScheduleView::class)->name('schedule');
Route::get('/presences', AttendancesIndex::class)->name('attendances.index');
Route::get('/evaluations', AssessmentsIndex::class)->name('assessments.index');
Route::get('/evaluations/{assessment}/notes', AssessmentGrades::class)->name('assessments.grades');
