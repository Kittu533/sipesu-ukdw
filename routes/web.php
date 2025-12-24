<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AlumniCertificateController;
use App\Http\Controllers\WithdrawalCertificateController;
use App\Http\Controllers\ActiveStudentCertificateController;
use App\Http\Controllers\StatementLetterController;
use App\Http\Controllers\DigitalSignatureController;
use App\Http\Controllers\Controller;



Route::get('/', function () {
    return view('landing'); // 'landing' merujuk pada file landing.blade.php
});

Route::get('/panduan/pengajuan', function () {
    return view('panduan.pengajuan');
})->name('panduan.pengajuan');

// PDF Generation Routes
Route::get('/pdf/alumni-certificate', [AlumniCertificateController::class, 'generatePDF'])->name('pdf.alumni-certificate');
Route::get('/pdf/withdrawal-certificate', [WithdrawalCertificateController::class, 'generatePDF'])->name('pdf.withdrawal-certificate');
Route::get('/pdf/active-student-certificate', [ActiveStudentCertificateController::class, 'generatePDF'])->name('pdf.active-student-certificate');
Route::get('/pdf/statement-letter', [StatementLetterController::class, 'generatePDF'])->name('pdf.statement-letter');
Route::get('/pdf/form', function () {
    return view('pdf.form');
})->name('pdf.form');


// Rute Halaman Login (Guest Only)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Rute Logout (Auth Only)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    // Dashboard (Default)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pengajuan Surat
    Route::get('/submission/create', [SubmissionController::class, 'create'])->name('submission.create');
    Route::post('/submission', [SubmissionController::class, 'store'])->name('submission.store');

    // Status Surat (Sedang diproses)
    Route::get('/submission/status', [SubmissionController::class, 'status'])->name('submission.status'); 

    // Riwayat Surat (Selesai/Ditolak)
    Route::get('/submission/history', [SubmissionController::class, 'history'])->name('submission.history');

    // Download completed letter
    Route::get('/submission/{id}/download', [SubmissionController::class, 'download'])->name('submission.download');

    // Persetujuan Surat (Pejabat)
    Route::get('/pejabat/approval', [SubmissionController::class, 'approvalList'])->name('pejabat.approval');
    Route::get('/pejabat/history', [SubmissionController::class, 'approvalHistory'])->name('pejabat.history');
    Route::get('/submission/{id}/approve', [SubmissionController::class, 'showApproval'])->name('submission.approve');
    Route::post('/submission/{id}/approve', [SubmissionController::class, 'processApproval'])->name('submission.process_approval');
    
    // Digital Signature Management (Pejabat)
    Route::get('/pejabat/digital-signature', [DigitalSignatureController::class, 'index'])->name('pejabat.digital-signature.index');
    Route::get('/pejabat/digital-signature/create', [DigitalSignatureController::class, 'create'])->name('pejabat.digital-signature.create');
    Route::post('/pejabat/digital-signature', [DigitalSignatureController::class, 'store'])->name('pejabat.digital-signature.store');
    Route::get('/pejabat/digital-signature/{id}/edit', [DigitalSignatureController::class, 'edit'])->name('pejabat.digital-signature.edit');
    Route::put('/pejabat/digital-signature/{id}', [DigitalSignatureController::class, 'update'])->name('pejabat.digital-signature.update');
    Route::delete('/pejabat/digital-signature/{id}', [DigitalSignatureController::class, 'destroy'])->name('pejabat.digital-signature.destroy');
    
    // --- ADMIN ROUTES ---
    Route::get('/admin/mahasiswa', [App\Http\Controllers\AdminController::class, 'mahasiswaIndex'])->name('admin.mahasiswa.index');
    Route::get('/admin/prodi', [App\Http\Controllers\AdminController::class, 'prodiIndex'])->name('admin.prodi.index');
    Route::get('/admin/submissions', [SubmissionController::class, 'adminIndex'])->name('admin.submission.index');
    Route::get('/admin/submissions/{id}/detail', [SubmissionController::class, 'adminDetail'])->name('admin.submission.detail');
    Route::get('/admin/submissions/{id}/print', [SubmissionController::class, 'adminPrint'])->name('admin.submission.print');
    
    // --- STAFF ROUTES ---
    Route::get('/staff/validation', [App\Http\Controllers\StaffController::class, 'validationList'])->name('staff.validation.index');
    Route::post('/staff/validation/{id}', [App\Http\Controllers\StaffController::class, 'processValidation'])->name('staff.validation.process');

    // Arsip Surat (General Archive - bisa diakses admin/staff/pejabat)
    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');
    Route::get('/archive/{id}/detail', [ArchiveController::class, 'detail'])->name('archive.detail');
    Route::get('/archive/{id}/print', [ArchiveController::class, 'print'])->name('archive.print');
    Route::get('/archive/{id}/download', [ArchiveController::class, 'download'])->name('archive.download'); 

    // Pengaturan
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // Sesuaikan dengan controller autentikasi Anda
});