<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImport implements ToCollection
{
    use Importable;
    public function collection(Collection $rows)
    {

    }
}

?>
