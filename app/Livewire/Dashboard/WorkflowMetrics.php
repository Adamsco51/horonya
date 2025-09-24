<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Suivie;
use App\Models\EtapeTraitement;
use App\Models\SuiviTraitement;
use Carbon\Carbon;

/**
 * Composant Livewire pour afficher les métriques du workflow
 * Fournit des statistiques sur les suivis, étapes et performances
 */
class WorkflowMetrics extends Component
{
    public $totalSuivis;
    public $suivisEnCours;
    public $suivisTermines;
    public $suivisEnRetard;
    public $tauxCompletion;
    public $etapesEnCours;
    public $etapesTerminees;
    public $etapesMoyenneParSuivi;
    public $tempsTraitementMoyen;

    /**
     * Montage du composant
     */
    public function mount()
    {
        $this->loadMetrics();
    }

    /**
     * Charger toutes les métriques
     */
    public function loadMetrics()
    {
        $this->loadSuivisMetrics();
        $this->loadEtapesMetrics();
        $this->loadPerformanceMetrics();
    }

    /**
     * Charger les métriques des suivis
     */
    private function loadSuivisMetrics()
    {
        $this->totalSuivis = Suivie::count();
        $this->suivisEnCours = Suivie::where('etat', Suivie::ETAT_EN_COURS)->count();
        $this->suivisTermines = Suivie::where('etat', Suivie::ETAT_ARRIVE)->count();
        $this->suivisEnRetard = Suivie::where('etat', Suivie::ETAT_RETARD)->count();
        
        // Calculer le taux de completion
        $this->tauxCompletion = $this->totalSuivis > 0 
            ? round(($this->suivisTermines / $this->totalSuivis) * 100, 1)
            : 0;
    }

    /**
     * Charger les métriques des étapes
     */
    private function loadEtapesMetrics()
    {
        $this->etapesEnCours = SuiviTraitement::where('statut', 'en_cours')->count();
        $this->etapesTerminees = SuiviTraitement::where('statut', 'termine')->count();
        
        // Calculer la moyenne d'étapes par suivi
        $totalEtapes = SuiviTraitement::count();
        $this->etapesMoyenneParSuivi = $this->totalSuivis > 0 
            ? round($totalEtapes / $this->totalSuivis, 1)
            : 0;
    }

    /**
     * Charger les métriques de performance
     */
    private function loadPerformanceMetrics()
    {
        // Calculer le temps de traitement moyen
        $suivisTermines = Suivie::where('etat', Suivie::ETAT_ARRIVE)
            ->whereNotNull('created_at')
            ->get();

        if ($suivisTermines->count() > 0) {
            $totalJours = 0;
            foreach ($suivisTermines as $suivi) {
                $dernierTraitement = $suivi->suiviTraitements()
                    ->where('statut', 'termine')
                    ->latest('date_realisation')
                    ->first();
                
                if ($dernierTraitement) {
                    $totalJours += $suivi->created_at->diffInDays($dernierTraitement->date_realisation);
                }
            }
            
            $this->tempsTraitementMoyen = round($totalJours / $suivisTermines->count(), 1);
        } else {
            $this->tempsTraitementMoyen = 0;
        }
    }

    /**
     * Obtenir les suivis critiques (en retard ou bloqués)
     */
    public function getSuivisCritiques()
    {
        return Suivie::with(['client', 'bilsOflading', 'agentTransit'])
            ->where(function($query) {
                $query->where('etat', Suivie::ETAT_RETARD)
                      ->orWhereHas('suiviTraitements', function($q) {
                          $q->where('statut', 'bloque');
                      });
            })
            ->orderBy('ETA', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Obtenir les étapes les plus utilisées
     */
    public function getEtapesPopulaires()
    {
        return EtapeTraitement::withCount('suiviTraitements')
            ->orderBy('suivi_traitements_count', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Obtenir les données pour le graphique de progression mensuelle
     */
    public function getProgressionMensuelle()
    {
        $derniersMois = collect();
        
        for ($i = 5; $i >= 0; $i--) {
            $mois = Carbon::now()->subMonths($i);
            $suivisTermines = Suivie::where('etat', Suivie::ETAT_ARRIVE)
                ->whereYear('updated_at', $mois->year)
                ->whereMonth('updated_at', $mois->month)
                ->count();
            
            $derniersMois->push([
                'mois' => $mois->format('M Y'),
                'termines' => $suivisTermines
            ]);
        }
        
        return $derniersMois;
    }

    /**
     * Rafraîchir les métriques
     */
    public function refresh()
    {
        $this->loadMetrics();
        session()->flash('message', 'Métriques mises à jour avec succès.');
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        return view('livewire.dashboard.workflow-metrics', [
            'suivisCritiques' => $this->getSuivisCritiques(),
            'etapesPopulaires' => $this->getEtapesPopulaires(),
            'progressionMensuelle' => $this->getProgressionMensuelle(),
        ]);
    }
}