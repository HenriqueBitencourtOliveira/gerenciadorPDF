@extends('layout')

@section('content')
    <h1>Upload de Arquivos PDF</h1>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="pdf_file" accept=".pdf">
        <button type="submit">Enviar PDF</button>
    </form>
@endsection
