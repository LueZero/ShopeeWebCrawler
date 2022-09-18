<?php

namespace Zero;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelGenerator
{
    /**
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    private $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    public function setTitle($title)
    {
        $this->spreadsheet->getActiveSheet()->setTitle($title);
    }

    public function setCellValue($column, $name)
    {
        $this->spreadsheet->getActiveSheet()->setCellValue($column, $name);
    }

    public function fromArray($database, $column)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->fromArray($database, NULL, $column);
    }

    public function save($filename)
    {
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filename . ".xlsx");

        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);
    }
}