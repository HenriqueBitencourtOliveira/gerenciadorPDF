@extends('layout')

@section('content')
    <h1>Lista de PDFs</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    <ul class="pdfs_">
        @foreach ($pdfs as $pdf)
            <li>
                {{ $pdf->file_name }} 
                <a href="{{ route('pdfs.show', $pdf->id) }}">Ver</a>
                <form action="{{ route('pdfs.destroy', $pdf->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Deletar</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
