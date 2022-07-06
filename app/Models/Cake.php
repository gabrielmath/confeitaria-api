<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cake
 *
 * @property int $id
 * @property string $name
 * @property int $weight
 * @property string $value
 * @property int $available_quantity
 * @property string $unit_of_measure
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CakeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Cake newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cake newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cake query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cake whereAvailableQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cake whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cake whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cake whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cake whereUnitOfMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cake whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cake whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cake whereWeight($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CakeAwaitingList[] $awaitingLists
 * @property-read int|null $awaiting_lists_count
 */
class Cake extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'weight',
        'value',
        'available_quantity',
        'unit_of_measure'
    ];

    public function awaitingLists()
    {
        return $this->hasMany(CakeAwaitingList::class);
    }
}
