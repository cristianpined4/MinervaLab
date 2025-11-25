<?php

namespace App\Services;

use Codedge\Fpdf\Facades\Fpdf;

class FPDFF
{
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
        Fpdf::AddPage();
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

        return Fpdf::Output('S');
    }

    private function printHeader()
    {
        if ($this->title) {
            Fpdf::SetFont(...$this->titleFont);
            Fpdf::Cell(0, 10, $this->enc($this->title), 0, 1, 'C');
        }

        if ($this->subTitle) {
            Fpdf::SetFont(...$this->subTitleFont);
            Fpdf::Cell(0, 8, $this->enc($this->subTitle), 0, 1, 'C');
        }

        if ($this->date) {
            Fpdf::SetFont(...$this->dateFont);
            Fpdf::Cell(0, 6, $this->enc("Fecha: " . $this->date), 0, 1, 'R');
        }

        Fpdf::Ln(5);
    }

    private function printTableHeader()
    {
        Fpdf::SetFont('Arial', 'B', 11);

        foreach ($this->columns as $i => $colName) {

            $label = $this->columnLabels[$colName] ?? $colName;

            $width = $this->columnWidths[$i] ?? 30;

            Fpdf::Cell($width, 8, $this->enc($label), 1, 0, 'C');
        }

        Fpdf::Ln();
    }

    private function printTableRows(array $rows)
    {
        Fpdf::SetFont('Arial', '', 10);

        foreach ($rows as $row) {
            foreach ($this->columns as $i => $key) {

                $width = $this->columnWidths[$i] ?? 30;
                $value = $row[$key] ?? '';

                Fpdf::Cell($width, 7, $this->enc($value), 1, 0);
            }
            Fpdf::Ln();
        }
    }
}
