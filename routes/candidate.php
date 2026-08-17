<?php

use App\Livewire\Candidate\ApplicationWizard;
use App\Livewire\Candidate\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/candidater', ApplicationWizard::class)->name('applications.create');
