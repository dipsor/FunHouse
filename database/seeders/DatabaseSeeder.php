<?php

namespace Database\Seeders;

use App\Models\Rota;
use App\Models\Shift;
use App\Models\Shop;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $weekCommenceDate = Carbon::now()->next(1);
        $shop = Shop::factory()->create(['name' => 'Fun House']);

        $staff1 = Staff::factory()->create(['first_name' => 'Black', 'surname' => 'Widow']);
        $staff2 = Staff::factory()->create(['first_name' => 'Wolverine', 'surname' => '']);
        $staff3 = Staff::factory()->create(['first_name' => 'Thor', 'surname' => '']);
        $staff4 = Staff::factory()->create(['first_name' => 'Gamora', 'surname' => '']);

        $shop->staff()->saveMany([
            $staff1,
            $staff2,
            $staff3,
            $staff4,
        ]);

        $rota = Rota::factory()->create(['week_commence_date' => $weekCommenceDate]);
        $shop->rotas()->save($rota);

        // 5|----------------|20
        // 5|-------|11
        //           14|-----|20
        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff1->id,
            'start_time' => $this->generateDate($weekCommenceDate, 0, 5),
            'end_time' => $this->generateDate($weekCommenceDate, 0, 20),
        ]);

        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff2->id,
            'start_time' => $this->generateDate($weekCommenceDate, 0, 5),
            'end_time' => $this->generateDate($weekCommenceDate, 0, 11),
        ]);

        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff3->id,
            'start_time' => $this->generateDate($weekCommenceDate, 0, 14),
            'end_time' => $this->generateDate($weekCommenceDate, 0, 20),
        ]);

        // 5|---------------|20
        // 5|------------|15
        //          13|-----|20
        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff1->id,
            'start_time' => $this->generateDate($weekCommenceDate, 1, 5),
            'end_time' => $this->generateDate($weekCommenceDate, 1, 20),
        ]);

        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff2->id,
            'start_time' => $this->generateDate($weekCommenceDate, 1, 5),
            'end_time' => $this->generateDate($weekCommenceDate, 1, 15),
        ]);

        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff3->id,
            'start_time' => $this->generateDate($weekCommenceDate, 1, 13),
            'end_time' => $this->generateDate($weekCommenceDate, 1, 20),
        ]);

        // 13|-------|20
        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff4->id,
            'start_time' => $this->generateDate($weekCommenceDate, 2, 13),
            'end_time' => $this->generateDate($weekCommenceDate, 2, 20),
        ]);

        // 5|----------|15
        //      12|--------|20
        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff3->id,
            'start_time' => $this->generateDate($weekCommenceDate, 3, 5),
            'end_time' => $this->generateDate($weekCommenceDate, 3, 15),
        ]);

        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff4->id,
            'start_time' => $this->generateDate($weekCommenceDate, 3, 12),
            'end_time' => $this->generateDate($weekCommenceDate, 3, 20),
        ]);


        // |----------|
        //   |---------|
        //  |--------------|
        //    |----|
        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff1->id,
            'start_time' => $this->generateDate($weekCommenceDate, 4, 5),
            'end_time' => $this->generateDate($weekCommenceDate, 4, 15),
        ]);

        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff2->id,
            'start_time' => $this->generateDate($weekCommenceDate, 4, 7),
            'end_time' => $this->generateDate($weekCommenceDate, 4, 16),
        ]);

        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff3->id,
            'start_time' => $this->generateDate($weekCommenceDate, 4, 6),
            'end_time' => $this->generateDate($weekCommenceDate, 4, 20),
        ]);

        Shift::factory()->create([
            'rota_id' => $rota->id,
            'staff_id' => $staff4->id,
            'start_time' => $this->generateDate($weekCommenceDate, 4, 8),
            'end_time' => $this->generateDate($weekCommenceDate, 4, 12),
        ]);
    }

    /**
     * @param  Carbon  $startDate
     * @param  int  $days
     * @param  int  $hours
     *
     * @return Carbon
     */
    private function generateDate(Carbon $startDate, int $days = 0, int $hours = 0): Carbon
    {
        if (!$days && !$hours) {
            return new Carbon($startDate);
        }

        return (new Carbon($startDate))->addDays($days)->addHours($hours);
    }
}
