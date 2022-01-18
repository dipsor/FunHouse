<?php

namespace App\Services\Mannings;

use App\Models\Rota;
use App\Models\Shift;

class Allocator
{
    /**
     * @var array
     */
    private $mannings = [];

    /**
     * @param  Rota  $rota
     *
     * @return array
     */
    public function get(Rota $rota): array
    {
        foreach ($rota->shifts as $shift) {
            $this->allocateHourUnitsPerDay($this->mannings, $shift->start_time->format('l'), $shift);
        }

        $allocatedArray = $this->allocateEmptyDay();

        foreach ($this->mannings as $key => $day) {
            $this->mannings[$key] = $this->mannings[$key] + $allocatedArray;
            ksort($this->mannings[$key]);
        }

        return $this->calculateManningHours();
    }

    /**
     * @param  array  $shiftArray
     * @param  string  $day
     * @param  Shift  $shift
     */
    private function allocateHourUnitsPerDay(array &$shiftArray, string $day, Shift $shift): void
    {
        $this->getShiftIntervalASArrayOfHours($shiftArray, $day,  $shift);
    }

    /**
     * @param  array  $shiftArray
     * @param $day
     * @param  Shift  $shift
     */
    private function getShiftIntervalASArrayOfHours(array &$shiftArray, $day, Shift $shift): void
    {
        for ($i = $shift->start_time->hour; $i < $shift->end_time->hour; $i++) {
            $shiftArray[$day][$i][] = [
                'shift_id' => $shift->id,
                'staff' => $shift->staff->full_name
            ];
        }
    }

    /**
     * @return array
     */
    private function allocateEmptyDay(): array
    {
        $dayArray = [];

        for ($i = 0; $i < 24; $i++) {
            $dayArray[$i] = null;
        }

        return $dayArray;
    }

    /**
     * @return array
     */
    private function calculateManningHours(): array
    {
        $mannings = [];

        foreach($this->mannings as $day => $shift) {
            foreach ($shift as $key => $hour) {
                if ($hour !== null && count($hour) === 1) {
                    $mannings[$day . '--' . $hour[0]['staff']][] = array_merge($hour[0], ['hour' => $key]);
                }
            }
        }

        return $mannings;
    }
}
