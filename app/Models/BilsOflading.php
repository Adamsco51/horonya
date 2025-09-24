<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BilsOflading extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'bl_number',
        'nbr_conteneur',
        'type_of_conteneur',
        'category',
        'note',
        'type_travail_id',
        'client_id',
        'created_by',
        'ship_name',
    ];

    public function type_travail(): BelongsTo
    {
        return $this->belongsTo(TypeTravail::class, 'type_travail_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
