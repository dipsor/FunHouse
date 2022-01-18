<?php

namespace App\Services;

use App\Models\Rota;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class ManningResolver
{
    private $mannings = [];

    public function resolve(Rota $rota)
    {
        // @todo consider working with timestamps

        // get weekdays
        $weekdays = [];

        // consider only weekdays
        for ($i = 0; $i < 5; $i++) {
            $start = $rota->week_commence_date->addDay($i);
            $end = $rota->week_commence_date->addDay($i+1)->subSecond(1);

            $weekdays[$rota->week_commence_date->addDay($i)->format('l')] =
            [
                'start_day' => $start->toDateTimeString(),
                'end_day' => $end->toDateTimeString(),
            ];

            foreach ($rota->shifts as $shift) {
                if ($shift->start_time >= $start && $shift->end_time <= $end) {
                    $weekdays[$rota->week_commence_date->addDay($i)->format('l')]['shifts'][] = $shift->toArray();
                }
            }
        }

        $data = [];

        foreach ($weekdays as $key => $weekday) {
            if (isset($weekday['shifts']) && count($weekday['shifts']) === 1) {
                $timestamp = $weekday['shifts'][0]['end_date_timestamp'] - $weekday['shifts'][0]['start_date_timestamp'];
                $this->mannings[] = [
                    'start_manning' => [
                        'shift_id' => $weekday['shifts'][0]['id'],
                        'user_id' => $weekday['shifts'][0]['staff_id'],
                        'start' => $weekday['shifts'][0]['start_date_timestamp'],
                        'end' => $weekday['shifts'][0]['start_date_timestamp'],
                        'start_string' => Carbon::createFromTimestamp($weekday['shifts'][0]['start_date_timestamp'])->toDateTimeString(),
                        'end_string' => Carbon::createFromTimestamp($weekday['shifts'][0]['end_date_timestamp'])->toDateTimeString(),
                        'timestamp' => $timestamp,
                        'minutes' => CarbonInterval::seconds($timestamp)->totalMinutes,
                        'hours' => CarbonInterval::seconds($timestamp)->totalHours
                    ],
                ];

                continue;
            }

            if (isset($weekday['shifts']) && count($weekday['shifts']) > 1) {
                $data[] = $this->getMinMax($weekday['shifts']);
            }
        }

        foreach ($data as $dataItem) {
            $miningTimestampStart = $dataItem['min2']['start_date_timestamp'] - $dataItem['min']['start_date_timestamp'];
            $miningTimestampEnd = $dataItem['max']['end_date_timestamp'] - $dataItem['max2']['end_date_timestamp'];

            $this->mannings[] = [
                'start_manning' => [
                    'shift_id' => $dataItem['min']['id'],
                    'user_id' => $dataItem['min']['staff_id'],
                    'start' => $dataItem['min']['start_date_timestamp'],
                    'end' => $dataItem['min2']['start_date_timestamp'],
                    'start_string' => Carbon::createFromTimestamp($dataItem['min']['start_date_timestamp'])->toDateTimeString(),
                    'end_string' => Carbon::createFromTimestamp($dataItem['min2']['start_date_timestamp'])->toDateTimeString(),
                    'timestamp' => $miningTimestampStart,
                    'minutes' => CarbonInterval::seconds($miningTimestampStart)->totalMinutes,
                    'hours' => CarbonInterval::seconds($miningTimestampStart)->totalHours
                ],

                'end_manning' => [
                    'shift_id' => $dataItem['max']['id'],
                    'user_id' => $dataItem['max']['staff_id'],
                    'start' => $dataItem['max2']['end_date_timestamp'],
                    'end' => $dataItem['max']['end_date_timestamp'],
                    'start_string' => Carbon::createFromTimestamp($dataItem['max2']['end_date_timestamp'])->toDateTimeString(),
                    'end_string' => Carbon::createFromTimestamp($dataItem['max']['end_date_timestamp'])->toDateTimeString(),
                    'timestamp' => $miningTimestampEnd,
                    'minutes' => CarbonInterval::seconds($miningTimestampEnd)->totalMinutes,
                    'hours' => CarbonInterval::seconds($miningTimestampEnd)->totalHours
                ],
            ];
        }

        dd($this->mannings);
    }

    private function getMinMax($shifts)
    {
        usort($shifts, function($a, $b) {
            return $a['start_date_timestamp'] <=> $b['start_date_timestamp'];
        });

        $minimum = $shifts[0];
        $minimum2 = $shifts[1];


        usort($shifts, function($a, $b) {
            return $a['end_date_timestamp'] <=> $b['end_date_timestamp'];
        });


        $maximum = $shifts[count($shifts) - 1];
        $maximum2 = $shifts[count($shifts) - 2];

        return [
            'min' => $minimum,
            'min2' => $minimum2,
            'max' => $maximum,
            'max2' => $maximum2,
        ];
    }
}
