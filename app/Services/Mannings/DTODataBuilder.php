<?php

namespace App\Services\Mannings;

use App\Models\Rota;
use Spatie\DataTransferObject\DataTransferObject;

class DTODataBuilder
{
    /**
     * @var array $mannigsArray
     */
    private array $mannigsArray = [];

    private string $week_commence_date;

    /**
     * DTODataBuilder constructor.
     *
     * @param  array  $mannigsArray
     */
    public function __construct(array $mannigsArray, string $week_commence_date)
    {
        $this->mannigsArray = $mannigsArray;
        $this->week_commence_date = $week_commence_date;
    }

    /**
     * @return SingleManning
     *
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function getDTO(): SingleManning
    {
        $manningData = new ManningData($this->mannigsArray);


        return new SingleManning([
            'monday' => $manningData->getByDayKey(ManningData::MONDAY, 'count') * 60,
            'tuesday' => $manningData->getByDayKey(ManningData::TUESDAY, 'count') * 60,
            'wednesday' => $manningData->getByDayKey(ManningData::WEDNESDAY, 'count') * 60,
            'thursday' => $manningData->getByDayKey(ManningData::THURSDAY, 'count') * 60,
            'friday' => $manningData->getByDayKey(ManningData::FRIDAY, 'count') * 60,
            'saturday' => $manningData->getByDayKey(ManningData::SATURDAY, 'count') * 60,
            'sunday' => $manningData->getByDayKey(ManningData::SUNDAY, 'count') * 60,
            'wholeWeek' => $manningData->getFullWeek(),
            'weekCommenceDate' => $this->week_commence_date
        ]);
    }
}
