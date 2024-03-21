@extends('layout')

@section('details')
    <h1>Detalhes do PDF</h1>
    <p>File Path: {{ $pdf->file_path }}</p>
    <p>Upload Date: {{ date('d-m-Y H:i:s', strtotime($pdf->upload_date)) }}</p>
   
@endsection
