<?php

namespace App\Data;

use JsonSerializable;
use DateTime;

class ApiResponse implements JsonSerializable
{
    const STATUS_OK    = 'OK';
    const STATUS_ERROR = 'ERROR';

    const DESCRIPTION_IMPORT_UNSUCCESSFUL  = 'IMPORT UNSUCCESSFUL';
    const DESCRIPTION_FORMAT_INCORRECT     = 'XML FORMAT INCORRECT';

    public $status;
    public $description;
    public $timestamp;

    function __construct($status, $description, $timestamp) {
        $this->status = $status;
        $this->description = $description;
        $this->timestamp = $timestamp;
    }

    public function jsonSerialize() {
        return [
            'status'        => $this->status,
            'description'   => $this->description,
            'timestamp'     => $this->timestamp->format(DateTime::ISO8601)
        ];
    }

}