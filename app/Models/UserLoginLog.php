<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Modèle pour gérer les logs de connexion des utilisateurs
 */
class UserLoginLog extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'platform',
        'action',
        'logged_at',
        'additional_data',
    ];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'logged_at' => 'datetime',
        'additional_data' => 'array',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour filtrer par action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par utilisateur
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeInPeriod($query, Carbon $start, Carbon $end)
    {
        return $query->whereBetween('logged_at', [$start, $end]);
    }

    /**
     * Scope pour les connexions réussies
     */
    public function scopeSuccessfulLogins($query)
    {
        return $query->where('action', 'login');
    }

    /**
     * Scope pour les tentatives de connexion échouées
     */
    public function scopeFailedLogins($query)
    {
        return $query->where('action', 'failed_login');
    }

    /**
     * Créer un log de connexion
     */
    public static function createLog(int $userId, string $action, array $additionalData = []): self
    {
        $request = request();
        
        return self::create([
            'user_id' => $userId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device' => self::getDeviceInfo($request->userAgent()),
            'browser' => self::getBrowserInfo($request->userAgent()),
            'platform' => self::getPlatformInfo($request->userAgent()),
            'action' => $action,
            'logged_at' => now(),
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Extraire les informations du device depuis le user agent
     */
    private static function getDeviceInfo(?string $userAgent): ?string
    {
        if (!$userAgent) return null;
        
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            return 'Mobile';
        } elseif (preg_match('/Tablet/', $userAgent)) {
            return 'Tablet';
        }
        
        return 'Desktop';
    }

    /**
     * Extraire les informations du navigateur depuis le user agent
     */
    private static function getBrowserInfo(?string $userAgent): ?string
    {
        if (!$userAgent) return null;
        
        if (preg_match('/Chrome/', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Firefox/', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/Safari/', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Edge/', $userAgent)) {
            return 'Edge';
        }
        
        return 'Unknown';
    }

    /**
     * Extraire les informations de la plateforme depuis le user agent
     */
    private static function getPlatformInfo(?string $userAgent): ?string
    {
        if (!$userAgent) return null;
        
        if (preg_match('/Windows/', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/Mac/', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/Linux/', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/Android/', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iOS/', $userAgent)) {
            return 'iOS';
        }
        
        return 'Unknown';
    }
}
