<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapeTraitement extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'description',
        'ordre',
        'obligatoire',
        'active',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'obligatoire' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * Scope pour récupérer seulement les étapes actives.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope pour récupérer les étapes dans l'ordre.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }

    /**
     * Relation avec les suivis de traitement.
     */
    public function suiviTraitements()
    {
        return $this->hasMany(SuiviTraitement::class);
    }
}
