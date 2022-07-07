<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


/**
 * App\Models\WaitingList
 *
 * @property int $id
 * @property int $cake_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cake $cake
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\WaitingListFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|WaitingList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WaitingList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WaitingList query()
 * @method static \Illuminate\Database\Eloquent\Builder|WaitingList whereCakeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WaitingList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WaitingList whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WaitingList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WaitingList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WaitingList whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WaitingList extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['cake_id', 'name', 'email'];

    public function cake()
    {
        return $this->belongsTo(Cake::class);
    }
}
