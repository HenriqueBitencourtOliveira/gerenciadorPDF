<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;

Route::get('/', function () {
    return view('welcome');
});

// Rota para exibir o formulário de upload de PDF
Route::view('/upload-pdf', 'upload-pdf')->name('upload-pdf');

// Rota para processar o upload de PDF
Route::post('/upload', [PdfController::class, 'upload'])->name('upload');

// Rota para exibir a lista de PDFs
Route::get('/pdfs', [PdfController::class, 'index'])->name('pdfs.index');

// Rota para buscar um PDF por ID
Route::get('/pdfs/{id}', [PdfController::class, 'show'])->name('pdfs.show');

// Rota para deletar um PDF por ID
Route::delete('/pdfs/{id}', [PdfController::class, 'destroy'])->name('pdfs.destroy');

// Rota para exportar os PDFs em formato Excel
Route::get('/export-pdf-files', [PdfController::class, 'exportPdfFiles'])->name('export.pdf_files');





// Rota para servir arquivos CSS estáticos
Route::get('/css/{file}', function ($file) {
    $filePath = public_path('css/' . $file);
    if (file_exists($filePath)) {
        return response()->file($filePath);
    } else {
        abort(404);
    }
})->where('file', '.*\.css$');
