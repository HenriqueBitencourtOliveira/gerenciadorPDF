<?php
namespace App\Exports;

use App\Models\PdfFile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PdfFilesExport implements FromCollection, WithHeadings
{
    protected $pdfs;
    protected $headers;
    protected $columnWidths;
    
    public function __construct($pdfs, $headers)
    {
        $this->pdfs = $pdfs;
        $this->headers = $headers;
    }

    public function collection()
    {
        return $this->pdfs;
    }

    public function headings(): array
    {
        return $this->headers;
    }
}
