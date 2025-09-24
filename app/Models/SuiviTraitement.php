<?php

namespace App\Models;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SuiviTraitement extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'suivie_id',
        'etape_traitement_id',
        'user_id',
        'date_realisation',
        'commentaire',
        'documents',
        'statut',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_realisation' => 'datetime',
        'documents' => 'array',
    ];

    /**
     * Relation avec les documents joints
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Relation avec le suivi.
     */
    public function suivie()
    {
        return $this->belongsTo(Suivie::class);
    }

    /**
     * Relation avec l'étape de traitement.
     */
    public function etapeTraitement()
    {
        return $this->belongsTo(EtapeTraitement::class);
    }

    /**
     * Relation avec l'utilisateur qui a réalisé l'étape.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour récupérer les étapes terminées.
     */
    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }

    /**
     * Scope pour récupérer les étapes en cours.
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Scope pour récupérer les étapes bloquées.
     */
    public function scopeBloque($query)
    {
        return $query->where('statut', 'bloque');
    }
}
