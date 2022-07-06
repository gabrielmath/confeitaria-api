<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\CakeAwaitingList
 *
 * @property-read \App\Models\Cake|null $cake
 * @property-read \App\Models\Client|null $client
 * @method static \Illuminate\Database\Eloquent\Builder|CakeAwaitingList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CakeAwaitingList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CakeAwaitingList query()
 * @mixin \Eloquent
 */
class CakeAwaitingList extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['cake_id', 'client_id'];

    public function cake()
    {
        return $this->belongsTo(Cake::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
