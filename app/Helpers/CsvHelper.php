<?php

namespace App\Helpers;

use SplFileInfo;

class CsvHelper
{
    protected $csv = '';

    protected $file = null;
    protected $stream = null;

    public function __construct() {
    }

    /**
     * create Csv
     * @param array $list
     * @param array $header
     * @return void
     */
    public function simpleCreate(Array $list, Array $header = [], $delimiter = ',')
    {
        if (count($header) > 0) {
            array_unshift($list, $header);
        }
        $stream = fopen('php://temp', 'r+b');
        foreach ($list as $row) {
            fputcsv($stream, $row, $delimiter);
        }
        rewind($stream);
        $this->csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
    }

    /**
     * Download CSV
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function simpleDownload($filename="")
    {
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        );
        return \Response::make($this->csv, 200, $headers);
    }

    //

    public function initialize(Array $header = [], $delimiter = ',')
    {
        $this->stream = fopen('php://temp', 'r+b');
        fputcsv($this->stream, $header, $delimiter);
    }

    public function addRows(Array $list, $delimiter = ',')
    {
        foreach ($list as $row) {
            fputcsv($this->stream, $row, $delimiter);
        }
    }

    public function finalize()
    {
        rewind($this->stream);

        $this->file = fopen(storage_path('app/public/export.csv'), "w+");

        while (!feof($this->stream) ) {
            fwrite($this->file, str_replace(PHP_EOL, "\r\n", fgets($this->stream)));
        }

        fclose($this->file);
        fclose($this->stream);
    }

    /**
     * Download CSV
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function download($filename="")
    {
        $headers = array(
            'Content-Type' => 'text/csv',
        );

        $fileObject = new SplFileInfo(storage_path('app/public/export.csv'));

        return \Response::download($fileObject, $filename, $headers)->deleteFileAfterSend(true);
    }
}
