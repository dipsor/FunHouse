<?php

namespace Tests\Unit;

use App\Models\Rota;
use App\Models\Shift;
use App\Models\Shop;
use App\Models\Staff;
use App\Services\ManningResolver;
use Carbon\Carbon;
use Tests\TestCase;

class ManningResolverTest extends TestCase
{
    private $shop;
    private $staff1;
    private $staff2;
    private $staff3;
    private $staff4;
    private $rota;
    private $weekCommenceDate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->weekCommenceDate = Carbon::now()->next(1);
        $this->shop = Shop::factory()->create(['name' => 'Fun House']);

        $this->staff1 = Staff::factory()->create(['first_name' => 'Black', 'surname' => 'Widow']);
        $this->staff2 = Staff::factory()->create(['first_name' => 'Wolverine', 'surname' => '']);
        $this->staff3 = Staff::factory()->create(['first_name' => 'Thor', 'surname' => '']);
        $this->staff4 = Staff::factory()->create(['first_name' => 'Gamora', 'surname' => '']);

        $this->shop->staff()->saveMany([
            $this->staff1,
            $this->staff2,
            $this->staff3,
            $this->staff4,
        ]);

        $this->rota = Rota::factory()->create(['week_commence_date' => $this->weekCommenceDate]);
        $this->shop->rotas()->save($this->rota);
    }

    /**
     *
     */
    public function test_scenario_1_monday()
    {
        // monday (first day in a week)
        $day = 0;
        // 15 hours shift first day in week
        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff1->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, $day, 5),
            'end_time' => $this->generateDate($this->weekCommenceDate, $day, 20),
        ]);

        /** @var ManningResolver $service */
        $service = resolve(ManningResolver::class);
        $dto = $service->resolve($this->rota);

        // assert dto returns correct amount of minutes
        $this->assertEquals(15 * 60, $dto->monday);

        // assert there is only one staff member
        $this->assertEquals(1, count($dto->wholeWeek['monday']));
    }

    /**
     *
     */
    public function test_scenario_2_tuesday()
    {
        $day = 1;
        // 15 hours shift first day in week
        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff1->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, $day, 5),
            'end_time' => $this->generateDate($this->weekCommenceDate, $day, 10),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff2->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, $day, 10),
            'end_time' => $this->generateDate($this->weekCommenceDate, $day, 15),
        ]);

        /** @var ManningResolver $service */
        $service = resolve(ManningResolver::class);
        $dto = $service->resolve($this->rota);

        // assert there is 600 minutes returns as each staff has 300 manning minutes
        $this->assertEquals(10 * 60, $dto->tuesday);

        // assert there are 2 staff members
        $this->assertEquals(2, count($dto->wholeWeek['tuesday']));
    }

    /**
     *
     */
    public function test_scenario_3_wednesday()
    {
        $day = 2;

        // 15 hours shift first day in week
        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff1->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, $day, 5),
            'end_time' => $this->generateDate($this->weekCommenceDate, $day, 15),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff2->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, $day, 7),
            'end_time' => $this->generateDate($this->weekCommenceDate, $day, 22),
        ]);

        /** @var ManningResolver $service */
        $service = resolve(ManningResolver::class);
        $dto = $service->resolve($this->rota);

        // assert there is 540 minutes returns as each staff has 300 manning minutes
        $this->assertEquals(9 * 60, $dto->wednesday);

        // assert there are 2 staff members
        $this->assertEquals(2, count($dto->wholeWeek['wednesday']));
    }

    /**
     *
     */
    public function test_scenario_4_complex_week()
    {
        // Monday 1 staff member having 3 hours
        // 5|----------------|20
        // 5|-------|11
        //           14|-----|20
        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff1->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 0, 5),
            'end_time' => $this->generateDate($this->weekCommenceDate, 0, 20),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff2->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 0, 5),
            'end_time' => $this->generateDate($this->weekCommenceDate, 0, 11),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff3->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 0, 14),
            'end_time' => $this->generateDate($this->weekCommenceDate, 0, 20),
        ]);

        // Tuesday nobody
        // 5|---------------|20
        // 5|------------|15
        //          13|-----|20
        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff1->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 1, 5),
            'end_time' => $this->generateDate($this->weekCommenceDate, 1, 20),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff2->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 1, 5),
            'end_time' => $this->generateDate($this->weekCommenceDate, 1, 15),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff3->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 1, 13),
            'end_time' => $this->generateDate($this->weekCommenceDate, 1, 20),
        ]);

        // Wednesday one person
        // 13|-------|20
        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff4->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 2, 13),
            'end_time' => $this->generateDate($this->weekCommenceDate, 2, 20),
        ]);

        // Thursday 2 staff members
        // 5|----------|15
        //      12|--------|20
        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff3->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 3, 5),
            'end_time' => $this->generateDate($this->weekCommenceDate, 3, 15),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff4->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 3, 12),
            'end_time' => $this->generateDate($this->weekCommenceDate, 3, 20),
        ]);

        // Friday 2 staff members BW 1h, thor 4h
        // |----------|
        //   |---------|
        //  |--------------|
        //    |----|
        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff1->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 4, 5),
            'end_time' => $this->generateDate($this->weekCommenceDate, 4, 15),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff2->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 4, 7),
            'end_time' => $this->generateDate($this->weekCommenceDate, 4, 16),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff3->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 4, 6),
            'end_time' => $this->generateDate($this->weekCommenceDate, 4, 20),
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $this->staff4->id,
            'start_time' => $this->generateDate($this->weekCommenceDate, 4, 8),
            'end_time' => $this->generateDate($this->weekCommenceDate, 4, 12),
        ]);

        /** @var ManningResolver $service */
        $service = resolve(ManningResolver::class);
        $dto = $service->resolve($this->rota);

        // Black widow 3 hours
        $this->assertEquals(3 * 60, $dto->monday);

        // Tuesday Nobody has manning hours/minutes
        $this->assertEquals(0, $dto->tuesday);

        // Wednesday one person 7 hours
        $this->assertEquals(7 * 60, $dto->wednesday);

        // Thursday 2 staff members having 12h together
        $this->assertEquals(12 * 60, $dto->thursday);
        $this->assertEquals(7 * 60, $dto->wholeWeek['thursday']['thor']['manning_minutes']);
        $this->assertEquals(5 * 60, $dto->wholeWeek['thursday']['gamora']['manning_minutes']);

        // Thursday 2 staff members having 5h together
        $this->assertEquals(5 * 60, $dto->friday);
        $this->assertEquals(1 * 60, $dto->wholeWeek['friday']['black widow']['manning_minutes']);
        $this->assertEquals(4 * 60, $dto->wholeWeek['friday']['thor']['manning_minutes']);
    }
}
