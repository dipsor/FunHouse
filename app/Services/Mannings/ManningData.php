<?php

namespace App\Services\Mannings;

class ManningData
{
    const MONDAY = 'monday';
    const TUESDAY = 'tuesday';
    const WEDNESDAY = 'wednesday';
    const THURSDAY = 'thursday';
    const FRIDAY = 'friday';
    const SATURDAY = 'saturday';
    const SUNDAY = 'sunday';
    const WHOLE_WEEK = 'wholeWeek';

    const DAYS = [
        'monday' => self::MONDAY,
        'tuesday' => self::TUESDAY,
        'wednesday' => self::WEDNESDAY,
        'thursday' => self::THURSDAY,
        'friday' => self::FRIDAY,
        'saturday' => self::SATURDAY,
        'sunday' => self::SUNDAY,
        'wholeWeek' => self::WHOLE_WEEK,
    ];


    /**
     * @var array
     */
    private array $days = [];
    private array $mannigsArray = [];
    private array $fullWeek = [];

    /**
     * ManningData constructor.
     *
     * @param  array  $mannigsArray
     */
    public function __construct(array $mannigsArray)
    {
        $this->mannigsArray = $mannigsArray;

        $this->setData();
    }

    private function setData()
    {
        foreach ($this->mannigsArray as $key => $item) {
            $day = strtolower(explode('--', $key)[0]);
            $name = strtolower(explode('--', $key)[1]);
            $dayKey = self::DAYS[$day];

            if (!isset($this->days[$dayKey])) {
                $this->days[$dayKey] = [
                    'items' => [],
                    'count' => 0,
                    'meta' => [
                        'staff' => [],
                    ]
                ];
            }

            $this->days[$dayKey]['items'] = array_merge($this->days[$dayKey]['items'], $item);
            $this->days[$dayKey]['count'] = 0;
            $this->days[$dayKey]['meta']['staff'][] = [
                'name' => $name,
                'manning_minutes' => count($item) * 60,
            ];

        }


        foreach ($this->days as $key => $day) {
            $this->days[$key]['count'] = count($this->days[$key]['items']);

        }
    }

    public function getByDayKey(string $day, string $key): int
    {
        if (isset($this->days[$day]) && isset($this->days[$day][$key])) {
            return $this->days[$day][$key];
        }

        return 0;
    }

    public function getFullWeek()
    {
        $collection = collect($this->days);

        return $collection->map(function ($item) {
            return $item['meta'];
        })->map(function($item) {
            return $item['staff'];
        })->toArray();
    }
}
