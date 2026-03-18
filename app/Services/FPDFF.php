<?php

namespace App\Services;

use Codedge\Fpdf\Fpdf;

class FPDFF
{
    protected $fpdf;
    protected $title;
    protected $titleFont = ['Arial', 'B', 16];

    protected $subTitle;
    protected $subTitleFont = ['Arial', '', 12];

    protected $date;
    protected $dateFont = ['Arial', '', 10];

    protected $columns = [];
    protected $columnLabels = []; // etiquetas personalizadas

    protected $columnWidths = [];

    public function __construct()
    {
        $this->fpdf = app('fpdf') ?? new Fpdf();
        $this->fpdf->AddPage();
    }

    private function enc($text)
    {
        return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
    }

    public function setColumnLabels(array $labels)
    {
        $this->columnLabels = $labels;
        return $this;
    }

    public function setTitle($text)
    {
        $this->title = $text;
        return $this;
    }

    public function setTitleFont($family, $style, $size)
    {
        $this->titleFont = [$family, $style, $size];
        return $this;
    }

    public function setSubTitle($text)
    {
        $this->subTitle = $text;
        return $this;
    }

    public function setSubTitleFont($family, $style, $size)
    {
        $this->subTitleFont = [$family, $style, $size];
        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function setDateFont($family, $style, $size)
    {
        $this->dateFont = [$family, $style, $size];
        return $this;
    }

    public function setModelColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function setColumnWidths(array $widths)
    {
        $this->columnWidths = $widths;
        return $this;
    }

    public function build(array $data)
    {
        $this->printHeader();
        $this->printTableHeader();
        $this->printTableRows($data);

        return $this->fpdf->Output('S');
    }

    private function printHeader()
    {
        if ($this->title) {
            $this->fpdf->SetFont(...$this->titleFont);
            $this->fpdf->Cell(0, 10, $this->enc($this->title), 0, 1, 'C');
        }

        if ($this->subTitle) {
            $this->fpdf->SetFont(...$this->subTitleFont);
            $this->fpdf->Cell(0, 8, $this->enc($this->subTitle), 0, 1, 'C');
        }

        if ($this->date) {
            $this->fpdf->SetFont(...$this->dateFont);
            $this->fpdf->Cell(0, 6, $this->enc("Fecha: " . $this->date), 0, 1, 'R');
        }

        $this->fpdf->Ln(5);
    }

    private function printTableHeader()
    {
        $this->fpdf->SetFont('Arial', 'B', 11);

        foreach ($this->columns as $i => $colName) {

            $label = $this->columnLabels[$colName] ?? $colName;

            $width = $this->columnWidths[$i] ?? 30;

            $this->fpdf->Cell($width, 8, $this->enc($label), 1, 0, 'C');
        }

        $this->fpdf->Ln();
    }

    private function printTableRows(array $rows)
    {
        $this->fpdf->SetFont('Arial', '', 10);

        foreach ($rows as $row) {
            foreach ($this->columns as $i => $key) {

                $width = $this->columnWidths[$i] ?? 30;
                $value = $row[$key] ?? '';

                $this->fpdf->Cell($width, 7, $this->enc($value), 1, 0);
            }
            $this->fpdf->Ln();
        }
    }
}
