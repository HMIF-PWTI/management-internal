<?php

use App\Http\Controllers\Auth\ActivateAccountController;
use App\Http\Controllers\QrCodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HmifForms\FormController;
use App\Http\Controllers\HmifForms\PublicFormController;
use App\Http\Controllers\HmifForms\ResponseFileController;
use App\Filament\Pages\CreateHmifForm;
use App\Filament\Pages\FormsDashboard;
use App\Filament\Pages\HmifFormResponses;
use App\Filament\Pages\HmifFormTemplates;
use App\Filament\Pages\HmifFormBuilder;
use App\Filament\Pages\HmifFormPreview;
use App\Filament\Pages\HmifFormResponseDetail;
use App\Filament\Pages\MyHmifForms;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/aktivasi-akun/{user}', ActivateAccountController::class)
    ->name('akun.aktivasi');

Route::get('/admin/aktivasi-akun/{user}', ActivateAccountController::class)
    ->name('admin.akun.aktivasi');

Route::middleware('auth')->prefix('admin/hmif-forms')->name('hmif-forms.actions.')->group(function () {
    Route::get('/responses/files/{file}', ResponseFileController::class)->name('response-files.download');
    Route::post('/', [FormController::class, 'store'])->name('store');
    Route::put('/{form}', [FormController::class, 'update'])->name('update');
    Route::post('/{form}/builder', [FormController::class, 'saveBuilder'])->name('builder.save');
    Route::post('/{form}/questions', [FormController::class, 'addQuestion'])->name('questions.store');
    Route::put('/{form}/questions/{question}', [FormController::class, 'updateQuestion'])->name('questions.update');
    Route::post('/{form}/questions/{question}/duplicate', [FormController::class, 'duplicateQuestion'])->name('questions.duplicate');
    Route::delete('/{form}/questions/{question}', [FormController::class, 'deleteQuestion'])->name('questions.destroy');
    Route::get('/{form}/responses/export', [FormController::class, 'exportResponses'])->name('responses.export');
    Route::post('/{form}/publish', [FormController::class, 'publish'])->name('publish');
    Route::post('/{form}/unpublish', [FormController::class, 'unpublish'])->name('unpublish');
    Route::post('/{form}/duplicate', [FormController::class, 'duplicate'])->name('duplicate');
    Route::delete('/{form}', [FormController::class, 'destroy'])->name('destroy');
});

Route::middleware('auth')->post('/admin/qr-code-generator', [QrCodeController::class, 'store'])
    ->name('qr-code-generator.store');

Route::get('/qr/{code}', [QrCodeController::class, 'track'])
    ->name('qr.scan');

Route::middleware('auth')->prefix('forms')->group(function () {
    Route::get('/', fn () => redirect(FormsDashboard::getUrl()));
    Route::get('/create', fn () => redirect(CreateHmifForm::getUrl()));
    Route::get('/mine', fn () => redirect(MyHmifForms::getUrl()));
    Route::get('/my', fn () => redirect(MyHmifForms::getUrl()));
    Route::get('/responses', fn () => redirect(HmifFormResponses::getUrl()));
    Route::get('/templates', fn () => redirect(HmifFormTemplates::getUrl()));
    Route::get('/{form}/edit', fn (string $form) => redirect(HmifFormBuilder::getUrl(['form' => $form])));
    Route::get('/{form}/responses', fn (string $form) => redirect(HmifFormResponseDetail::getUrl(['form' => $form])));
    Route::get('/{form}/preview', fn (string $form) => redirect(HmifFormPreview::getUrl(['form' => $form])));
});

Route::get('/f/{token}', [PublicFormController::class, 'show'])->name('hmif-forms.public.show');
Route::post('/f/{token}', [PublicFormController::class, 'submit'])->name('hmif-forms.public.submit');
