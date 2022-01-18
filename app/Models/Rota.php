<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Rota
 *
 * @property int $id
 * @property int $shop_id
 * @property string $week_commence_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shift[] $shifts
 * @property-read int|null $shifts_count
 * @method static \Database\Factories\RotaFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Rota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rota query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rota whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rota whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rota whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rota whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rota whereWeekCommenceDate($value)
 * @mixin \Eloquent
 */
class Rota extends Model
{
    use HasFactory;

    protected $casts = [
        'week_commence_date' => 'datetime'
    ];


    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
