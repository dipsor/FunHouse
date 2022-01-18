<?php

namespace App\Services\Mannings;

use Spatie\DataTransferObject\DataTransferObject;

class SingleManning extends DataTransferObject
{
    public int $monday;
    public int $tuesday;
    public int $wednesday;
    public int $thursday;
    public int $friday;
    public int $saturday;
    public int $sunday;
    public array $wholeWeek;
    public string $weekCommenceDate;
}
