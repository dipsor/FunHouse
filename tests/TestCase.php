<?php

namespace Tests;

use Carbon\Carbon;
use Carbon\Traits\Creator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        RefreshDatabase;

    /**
     * @param  Carbon  $startDate
     * @param  int  $days
     * @param  int  $hours
     *
     * @return Carbon
     */
    protected function generateDate(Carbon $startDate, int $days = 0, int $hours = 0): Carbon
    {
        if (!$days && !$hours) {
            return new Carbon($startDate);
        }

        return (new Carbon($startDate))->addDays($days)->addHours($hours);
    }
}
