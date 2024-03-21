<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Arquivos PDF</title>
   
    <link href="{{ asset('app.css') }}" rel="stylesheet">


    
</head>
<body>
    <!-- Navbar -->
    <nav>
    <a href="{{ route('upload-pdf') }}">Upload de PDF</a>
        <a href="{{ route('pdfs.index') }}">Lista de PDFs</a>
        <a href="{{ route('export.pdf_files') }}">Exportar PDFs</a>
       
        
    </nav>
    <!-- Conteúdo da Página -->
    <div class="content">
        @yield('content') <!-- Aqui será incluído o conteúdo específico de cada página -->
        @yield('details')
    </div>
</body>
</html>
