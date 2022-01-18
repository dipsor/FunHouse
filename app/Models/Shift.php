<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Shift
 *
 * @property int $id
 * @property int $rota_id
 * @property int $staff_id
 * @property string $start_time
 * @property string $end_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ShiftFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereRotaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shift whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Shift extends Model
{
    use HasFactory;

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected $appends = [
        'start_date_timestamp',
        'end_date_timestamp',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function getStartDateTimestampAttribute()
    {
        return $this->start_time->timestamp;
    }

    public function getEndDateTimestampAttribute()
    {
        return $this->end_time->timestamp;
    }

}
