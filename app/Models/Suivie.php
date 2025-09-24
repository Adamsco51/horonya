<?php

namespace App\Models;

use App\Models\EtapeTraitement;
use App\Models\SuiviTraitement;
use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Suivie extends Model
{
    protected $fillable = [
        'bils_ofladings_id',
        'client_id',
        'created_by',
        'ETA',
        'status',
        'etat',
        'agent_transit_id',
        'numero_bl',
    ];

    protected $casts = [
        'ETA' => 'date',
    ];

    // Constantes pour les statuts
    const STATUS_STOCKAGE = 'stockage';
    const STATUS_TRAITEMENT = 'traitement';

    // Constantes pour les états
    const ETAT_EN_COURS = 'en_cours';
    const ETAT_ARRIVE = 'arrive';
    const ETAT_RETARD = 'retard';

    /**
     * Relation avec l'utilisateur créateur
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec les documents joints
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Relation avec les traitements du suivi.
     */
    public function suiviTraitements()
    {
        return $this->hasMany(SuiviTraitement::class);
    }

    /**
     * Obtenir le pourcentage de progression du traitement.
     */
    public function getPourcentageProgressionAttribute()
    {
        $totalEtapes = EtapeTraitement::active()->count();
        if ($totalEtapes === 0) {
            return 0;
        }
        
        $etapesTerminees = $this->suiviTraitements()->termine()->count();
        return round(($etapesTerminees / $totalEtapes) * 100, 2);
    }

    /**
     * Vérifier si le traitement est terminé.
     */
    public function getTraitementTermineAttribute()
    {
        $totalEtapes = EtapeTraitement::active()->count();
        $etapesTerminees = $this->suiviTraitements()->termine()->count();
        
        return $totalEtapes > 0 && $etapesTerminees === $totalEtapes;
    }

    /**
     * Mettre à jour automatiquement le statut basé sur la progression des étapes.
     */
    public function updateStatusAutomatiquement()
    {
        // Si aucune étape n'a été commencée, rester en stockage
        if ($this->suiviTraitements()->count() === 0) {
            if ($this->status !== self::STATUS_STOCKAGE) {
                $this->update(['status' => self::STATUS_STOCKAGE]);
            }
            return;
        }

        // Si des étapes sont en cours ou terminées, passer en traitement
        if ($this->status === self::STATUS_STOCKAGE && $this->suiviTraitements()->count() > 0) {
            $this->update(['status' => self::STATUS_TRAITEMENT]);
        }

        // Mettre à jour l'état basé sur la progression
        $this->updateEtatAutomatiquement();
    }

    /**
     * Mettre à jour automatiquement l'état basé sur la progression et l'ETA.
     */
    public function updateEtatAutomatiquement()
    {
        // Si le traitement est terminé
        if ($this->traitement_termine) {
            if ($this->etat !== self::ETAT_ARRIVE) {
                $this->update(['etat' => self::ETAT_ARRIVE]);
            }
            return;
        }

        // Vérifier si en retard
        if ($this->isLate()) {
            if ($this->etat !== self::ETAT_RETARD) {
                $this->update(['etat' => self::ETAT_RETARD]);
            }
            return;
        }

        // Sinon, en cours
        if ($this->etat !== self::ETAT_EN_COURS) {
            $this->update(['etat' => self::ETAT_EN_COURS]);
        }
    }

    /**
     * Vérifier si des étapes obligatoires sont manquantes.
     */
    public function getEtapesObligatoiresManquantesAttribute()
    {
        $etapesObligatoires = EtapeTraitement::active()->where('obligatoire', true)->get();
        $etapesRealisees = $this->suiviTraitements()->pluck('etape_traitement_id');
        
        return $etapesObligatoires->whereNotIn('id', $etapesRealisees);
    }

    /**
     * Vérifier si toutes les étapes obligatoires sont terminées.
     */
    public function getEtapesObligatoiresTermineesAttribute()
    {
        $etapesObligatoires = EtapeTraitement::active()->where('obligatoire', true)->pluck('id');
        $etapesTerminees = $this->suiviTraitements()->termine()->pluck('etape_traitement_id');
        
        return $etapesObligatoires->diff($etapesTerminees)->isEmpty();
    }

    /**
     * Relation avec le BL (Bills of Lading)
     */
    public function bilsOflading(): BelongsTo
    {
        return $this->belongsTo(BilsOflading::class);
    }

    /**
     * Relation avec le client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation avec l'agent de transit assigné
     */
    public function agentTransit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_transit_id');
    }

    /**
     * Calcule le nombre de jours restants jusqu'à l'ETA
     */
    public function getJoursRestantsAttribute(): int
    {
        if (!$this->ETA) {
            return 0;
        }
        
        $today = Carbon::today();
        $eta = Carbon::parse($this->ETA);
        
        return (int) round($today->diffInDays($eta, false));
    }

    /**
     * Vérifie si le suivie nécessite une alerte (7 jours ou moins)
     */
    public function getNecessiteAlerteAttribute(): bool
    {
        return $this->jours_restants <= 7 && $this->jours_restants >= 0;
    }

    /**
     * Vérifie si le navire est en retard
     */
    public function getEnRetardAttribute(): bool
    {
        return $this->jours_restants < 0;
    }

    /**
     * Détermine automatiquement le statut basé sur les jours restants
     */
    public function determinerStatut(): string
    {
        if ($this->jours_restants <= 7) {
            return self::STATUS_TRAITEMENT;
        }
        
        return self::STATUS_STOCKAGE;
    }

    /**
     * Met à jour automatiquement le statut
     */
    public function updateStatut(): void
    {
        $this->update(['status' => $this->determinerStatut()]);
    }

    /**
     * Scope pour les suivies nécessitant une alerte
     */
    public function scopeAvecAlerte($query)
    {
        // Utilisation de fonctions SQLite compatibles au lieu de MySQL DATEDIFF et CURDATE
        return $query->whereRaw("julianday(ETA) - julianday('now') <= 7 AND julianday(ETA) - julianday('now') >= 0");
    }

    /**
     * Scope pour les suivies en traitement
     */
    public function scopeEnTraitement($query)
    {
        return $query->where('status', self::STATUS_TRAITEMENT);
    }

    /**
     * Scope pour les suivies en stockage
     */
    public function scopeEnStockage($query)
    {
        return $query->where('status', self::STATUS_STOCKAGE);
    }
}
