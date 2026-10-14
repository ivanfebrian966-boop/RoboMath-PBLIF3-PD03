<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class SimpleXlsxExporter
{
    /**
     * Download an array of data as an Excel (.xlsx) file.
     *
     * @param  string  $filename  Filename ending with .xlsx
     * @param  array<int, string>  $headers  Header column names
     * @param  array<int, array<int, mixed>>  $rows  Data rows
     */
    public static function download(string $filename, array $headers, array $rows, string $sheetTitle = 'Laporan'): BinaryFileResponse
    {
        if (! str_ends_with(strtolower($filename), '.xlsx')) {
            $filename .= '.xlsx';
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');

        $zip = new ZipArchive;
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Cannot create temporary XLSX file');
        }

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
        $zip->addFromString('_rels/.rels', $rootRels);

        // 3. xl/workbook.xml
        $cleanSheetTitle = htmlspecialchars(mb_substr($sheetTitle, 0, 31), ENT_XML1, 'UTF-8');
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets>'
            .'<sheet name="'.$cleanSheetTitle.'" sheetId="1" r:id="rId1"/>'
            .'</sheets>'
            .'</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // 4. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 5. xl/styles.xml (Professional RoboMath theme: indigo header, clean borders)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<fonts count="2">'
            .'<font><sz val="11"/><name val="Segoe UI"/><family val="2"/></font>'
            .'<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/><family val="2"/></font>'
            .'</fonts>'
            .'<fills count="3">'
            .'<fill><patternFill patternType="none"/></fill>'
            .'<fill><patternFill patternType="gray125"/></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF4F46E5"/></patternFill></fill>'
            .'</fills>'
            .'<borders count="2">'
            .'<border><left/><right/><top/><bottom/><diagonal/></border>'
            .'<border>'
            .'<left style="thin"><color rgb="FFE2E8F0"/></left>'
            .'<right style="thin"><color rgb="FFE2E8F0"/></right>'
            .'<top style="thin"><color rgb="FFE2E8F0"/></top>'
            .'<bottom style="thin"><color rgb="FFE2E8F0"/></bottom>'
            .'</border>'
            .'</borders>'
            .'<cellStyleXfs count="1">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>'
            .'</cellStyleXfs>'
            .'<cellXfs count="3">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"/>'
            .'<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyFont="1" applyBorder="1" applyAlignment="1"><alignment vertical="center"/></xf>'
            .'</cellXfs>'
            .'</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/sheet1.xml
        $sheetXml = self::buildWorksheetXml($headers, $rows);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();

        return response()->download(
            $tempFile,
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                'Cache-Control' => 'max-age=0, no-cache, must-revalidate',
                'Pragma' => 'public',
            ]
        )->deleteFileAfterSend(true);
    }

    /**
     * Build OpenXML worksheet XML.
     *
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, mixed>>  $rows
     */
    protected static function buildWorksheetXml(array $headers, array $rows): string
    {
        $colCount = count($headers);
        $rowCount = count($rows) + 1;
        $lastColLetter = self::colIndexToLetter($colCount);

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<dimension ref="A1:'.$lastColLetter.$rowCount.'"/>'
            .'<sheetViews><sheetView tabSelected="1" workbookViewId="0"/></sheetViews>'
            .'<sheetFormatPr defaultRowHeight="20"/>';

        // Column widths
        if ($colCount > 0) {
            $xml .= '<cols>';
            for ($c = 1; $c <= $colCount; $c++) {
                // Calculate dynamic width based on headers and sample rows
                $maxWidth = mb_strlen((string) ($headers[$c - 1] ?? '')) + 6;
                foreach (array_slice($rows, 0, 50) as $row) {
                    $val = (string) ($row[$c - 1] ?? '');
                    $maxWidth = max($maxWidth, min(mb_strlen($val) + 4, 45));
                }
                $xml .= '<col min="'.$c.'" max="'.$c.'" width="'.max($maxWidth, 14).'" customWidth="1"/>';
            }
            $xml .= '</cols>';
        }

        $xml .= '<sheetData>';

        // Row 1: Headers (style index 1 = bold white text with indigo fill)
        $xml .= '<row r="1" ht="26" customHeight="1">';
        foreach ($headers as $colIdx => $headerText) {
            $cellRef = self::colIndexToLetter($colIdx + 1).'1';
            $escaped = htmlspecialchars((string) $headerText, ENT_XML1, 'UTF-8');
            $xml .= '<c r="'.$cellRef.'" s="1" t="inlineStr"><is><t>'.$escaped.'</t></is></c>';
        }
        $xml .= '</row>';

        // Data rows (style index 2 = normal with borders)
        $rowNum = 2;
        foreach ($rows as $row) {
            $xml .= '<row r="'.$rowNum.'" ht="22" customHeight="1">';
            foreach (array_values($row) as $colIdx => $val) {
                $cellRef = self::colIndexToLetter($colIdx + 1).$rowNum;

                if (is_int($val) || (is_float($val) && ! is_nan($val))) {
                    $xml .= '<c r="'.$cellRef.'" s="2" t="n"><v>'.$val.'</v></c>';
                } elseif ($val === null || $val === '') {
                    $xml .= '<c r="'.$cellRef.'" s="2"/>';
                } else {
                    $cleanStr = htmlspecialchars((string) $val, ENT_XML1, 'UTF-8');
                    $xml .= '<c r="'.$cellRef.'" s="2" t="inlineStr"><is><t>'.$cleanStr.'</t></is></c>';
                }
            }
            $xml .= '</row>';
            $rowNum++;
        }

        $xml .= '</sheetData>';
        $xml .= '</worksheet>';

        return $xml;
    }

    /**
     * Convert 1-indexed column number to Excel column letter (1 -> A, 27 -> AA).
     */
    public static function colIndexToLetter(int $colIndex): string
    {
        $letter = '';
        while ($colIndex > 0) {
            $mod = ($colIndex - 1) % 26;
            $letter = chr(65 + $mod).$letter;
            $colIndex = (int) (($colIndex - $mod) / 26);
        }

        return $letter;
    }
}
