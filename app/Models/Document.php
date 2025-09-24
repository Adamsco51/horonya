<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * Modèle pour gérer les documents joints
 * Utilise une relation polymorphe pour être attaché à différents modèles
 */
class Document extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'nom_original',
        'nom_stockage',
        'chemin',
        'type_mime',
        'taille',
        'extension',
        'documentable_type',
        'documentable_id',
        'user_id',
        'description',
    ];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'taille' => 'integer',
    ];

    /**
     * Relation polymorphe vers l'entité parente
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relation vers l'utilisateur qui a uploadé le document
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir la taille formatée du fichier
     */
    public function getTailleFormatteeAttribute(): string
    {
        $bytes = $this->taille;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Vérifier si le fichier existe sur le disque
     */
    public function exists(): bool
    {
        return Storage::exists($this->chemin);
    }

    /**
     * Obtenir l'URL de téléchargement du document
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('documents.download', $this->id);
    }

    /**
     * Supprimer le fichier du disque lors de la suppression du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            if (Storage::exists($document->chemin)) {
                Storage::delete($document->chemin);
            }
        });
    }

    /**
     * Scope pour filtrer par type de fichier
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type_mime', 'like', $type . '%');
    }

    /**
     * Scope pour filtrer par extension
     */
    public function scopeByExtension($query, string $extension)
    {
        return $query->where('extension', $extension);
    }

    /**
     * Scope pour filtrer par utilisateur
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
