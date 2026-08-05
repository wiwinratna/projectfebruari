<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CardTemplateExport implements WithMultipleSheets
{
    protected $eventId;

    public function __construct($eventId = null)
    {
        $this->eventId = $eventId;
    }

    public function sheets(): array
    {
        return [
            new CardTemplateDataSheet($this->eventId),
            new CardTemplateReferenceSheet($this->eventId),
            new CardTemplateInstructionSheet(),
        ];
    }
}
