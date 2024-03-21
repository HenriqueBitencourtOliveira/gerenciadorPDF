<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PdfFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PdfFilesExport;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use App\Models\PdfData;
use Spatie\PdfToText\Pdf;




class PdfController extends Controller
{

    public function index()
    {
        $pdfs = PdfFile::all();
        return view('pdfs.index', compact('pdfs'));
    }

    public function show($id)
    {
        $pdf = PdfFile::findOrFail($id);
        return view('pdfs.show', compact('pdf'));
       
    }

    public function destroy($id)
    {
        $pdf = PdfFile::findOrFail($id);
        $pdf->delete();
        return redirect()->route('pdfs.index')->with('success', 'PDF deletado com sucesso.');
    }

    public function exportPdfFiles()
    {
        // Obtém os dados dos PDFs
        $pdfs = PdfFile::all();

        // Define os cabeçalhos das colunas
        $headers = ['ID', 'File_name', 'Text', 'File_path', 'Upload_date'];

        // Adiciona os dados e os cabeçalhos ao arquivo Excel
        return Excel::download(new PdfFilesExport($pdfs, $headers), 'pdf_files.xlsx');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10240', // Validação: arquivo PDF, máximo 10MB
        ]);

        if ($request->file('pdf_file')->isValid()) {
            $fileName = $request->file('pdf_file')->getClientOriginalName(); // Obtém o nome original do arquivo

            // Verifica se o arquivo com o mesmo nome já existe no sistema de arquivos
            if (Storage::exists('pdfs/' . $fileName)) {
                // Se o arquivo existir, exclua o arquivo existente antes de salvar o novo
                Storage::delete('pdfs/' . $fileName);
            }

            // Salva o novo arquivo com o mesmo nome na pasta 'pdfs'
            $filePath = $request->file('pdf_file')->storeAs('pdfs', $fileName);
            
            // Verifica se já existe um registro para o arquivo no banco de dados
            $existingFile = PdfFile::where('file_name', $fileName)->first();
            $filePath = storage_path('app/' . $filePath);

            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($filePath);
            $text = $pdf->getText();
            $itemData = $this->extractItemData($text);
            // Se existir, atualize o registro com as informações do novo arquivo
            if ($existingFile) {
                $existingFile->file_path = $filePath;
                $existingFile->upload_date = now()->format('Y-m-d H:i:s');
                $existingFile->text = $text;
                $existingFile->item_data = json_encode($itemData);
                $existingFile->save();
            } else {
                // Caso contrário, crie um novo registro no banco de dados
                $pdfFile = new PdfFile();
                $pdfFile->file_path = $filePath;
                $pdfFile->file_name = $fileName;
                $pdfFile->upload_date = now()->format('Y-m-d H:i:s');
                $pdfFile->text = $text;
                $pdfFile->item_data = json_encode($itemData);
                $pdfFile->save();
                
                
            }

            return redirect()->back()->with('success', 'PDF enviado com sucesso.');
        }

        return redirect()->back()->withErrors(['Erro ao enviar o PDF.']);
    }

    public function extractItemData($text)
{
    // Define um padrão para extrair os dados de cada item do pedido
    
    $pattern = '/\b(\d{5})\s+(\d{14})\s+(.+?)\s+(\d+)\s+(.+?)\s+(\d{2}\.\d{2}\.\d{4})\s+(\d+,\d+)\s+(\d+,\d+)\b/s';

    // Inicializa um array para armazenar os dados dos itens do pedido
    $itemData = [];

    // Realiza a correspondência com o padrão em todo o texto
    preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

    // Itera sobre os resultados da correspondência
    foreach ($matches as $match) {
        // Extrai os dados de cada item do pedido
        $item = [
            'Item' => $match[1],
            'Material' => $match[2],
            'Descrição' => $match[3],
            'Quantidade' => $match[4],
            'Data de Entrega' => $match[6],
            'Preço Unitário' => $match[7],
            'Total' => $match[8],
        ];

        // Adiciona os dados do item ao array de dados dos itens do pedido
        $itemData[] = $item;
    }

    return $itemData;
}

    

}
