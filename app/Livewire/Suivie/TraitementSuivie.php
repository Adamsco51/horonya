<?php

namespace App\Livewire\Suivie;

use App\Models\Suivie;
use App\Models\EtapeTraitement;
use App\Models\SuiviTraitement;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TraitementSuivie extends Component
{
    use WithFileUploads;

    public $suivie;
    public $etapes;
    public $suiviTraitements;
    
    // Propriétés pour l'ajout d'une nouvelle étape
    public $selectedEtapeId;
    public $commentaire;
    public $documents = [];
    public $statut = 'en_cours';

    /**
     * Règles de validation
     */
    protected $rules = [
        'selectedEtapeId' => 'required|exists:etape_traitements,id',
        'commentaire' => 'nullable|string|max:1000',
        'documents.*' => 'nullable|file|max:10240', // 10MB max par fichier
        'statut' => 'required|in:en_cours,termine,bloque',
    ];

    /**
     * Messages de validation personnalisés
     */
    protected $messages = [
        'selectedEtapeId.required' => 'Veuillez sélectionner une étape.',
        'selectedEtapeId.exists' => 'L\'étape sélectionnée n\'existe pas.',
        'commentaire.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        'documents.*.file' => 'Le fichier doit être valide.',
        'documents.*.max' => 'Chaque fichier ne peut pas dépasser 10MB.',
        'statut.required' => 'Le statut est requis.',
        'statut.in' => 'Le statut doit être: en cours, terminé ou bloqué.',
    ];

    /**
     * Montage du composant
     */
    public function mount(Suivie $suivie)
    {
        $this->suivie = $suivie;
        $this->loadData();
    }

    /**
     * Charger les données nécessaires
     */
    public function loadData()
    {
        $this->etapes = EtapeTraitement::active()->ordered()->get();
        $this->suiviTraitements = $this->suivie->suiviTraitements()
            ->with(['etapeTraitement', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Ajouter une nouvelle étape de traitement
     */
    public function ajouterEtape()
    {
        $this->validate();

        // Valider les prérequis de l'étape
        if (!$this->validateEtapePrerequisites($this->selectedEtapeId)) {
            return;
        }

        // Vérifier si l'étape n'a pas déjà été ajoutée
        $existingEtape = $this->suivie->suiviTraitements()
            ->where('etape_traitement_id', $this->selectedEtapeId)
            ->first();

        if ($existingEtape) {
            $this->addError('selectedEtapeId', 'Cette étape a déjà été ajoutée à ce suivi.');
            return;
        }

        // Traitement des documents
        $documentsPath = [];
        if (!empty($this->documents)) {
            foreach ($this->documents as $document) {
                $path = $document->store('suivie-documents', 'public');
                $documentsPath[] = [
                    'name' => $document->getClientOriginalName(),
                    'path' => $path,
                    'size' => $document->getSize(),
                    'type' => $document->getMimeType(),
                ];
            }
        }

        // Créer l'enregistrement
        SuiviTraitement::create([
            'suivie_id' => $this->suivie->id,
            'etape_traitement_id' => $this->selectedEtapeId,
            'user_id' => Auth::id(),
            'date_realisation' => now(),
            'commentaire' => $this->commentaire,
            'documents' => $documentsPath,
            'statut' => $this->statut,
        ]);

        // Mettre à jour automatiquement le statut du suivi
        $this->suivie->updateStatusAutomatiquement();

        // Réinitialiser le formulaire
        $this->resetForm();
        
        // Recharger les données
        $this->loadData();

        // Message de succès
        session()->flash('message', 'Étape ajoutée avec succès.');

        // Vérifier si des notifications doivent être envoyées
        $this->checkAndSendNotifications();
    }

    /**
     * Mettre à jour le statut d'une étape
     */
    public function updateStatut($suiviTraitementId, $nouveauStatut)
    {
        $suiviTraitement = SuiviTraitement::find($suiviTraitementId);
        
        if ($suiviTraitement && $suiviTraitement->suivie_id === $this->suivie->id) {
            $suiviTraitement->update(['statut' => $nouveauStatut]);
            
            // Mettre à jour automatiquement le statut du suivi
            $this->suivie->updateStatusAutomatiquement();
            
            $this->loadData();
            session()->flash('message', 'Statut mis à jour avec succès.');
            
            // Vérifier si des notifications doivent être envoyées
            $this->checkAndSendNotifications();
        }
    }

    /**
     * Vérifier et envoyer des notifications si nécessaire
     */
    private function checkAndSendNotifications()
    {
        // Notification si toutes les étapes obligatoires sont terminées
        if ($this->suivie->etapes_obligatoires_terminees) {
            session()->flash('info', 'Toutes les étapes obligatoires ont été terminées avec succès.');
        }

        // Notification si le traitement est terminé
        if ($this->suivie->traitement_termine) {
            session()->flash('success', 'Le traitement de ce suivi est maintenant terminé.');
        }

        // Notification si des étapes obligatoires sont manquantes
        $etapesManquantes = $this->suivie->etapes_obligatoires_manquantes;
        if ($etapesManquantes->count() > 0) {
            $noms = $etapesManquantes->pluck('nom')->join(', ');
            session()->flash('warning', "Étapes obligatoires manquantes : {$noms}");
        }

        // Notification si en retard
        if ($this->suivie->isLate()) {
            $joursRetard = abs($this->suivie->remainingDays());
            session()->flash('error', "Ce suivi est en retard de {$joursRetard} jour(s).");
        }
    }

    /**
     * Valider les prérequis avant d'ajouter une étape
     */
    private function validateEtapePrerequisites($etapeId)
    {
        $etape = EtapeTraitement::find($etapeId);
        
        if (!$etape) {
            return false;
        }

        // Vérifier si des étapes précédentes obligatoires sont manquantes
        $etapesPrecedentes = EtapeTraitement::active()
            ->where('obligatoire', true)
            ->where('ordre', '<', $etape->ordre)
            ->get();

        $etapesRealisees = $this->suivie->suiviTraitements()->pluck('etape_traitement_id');
        
        foreach ($etapesPrecedentes as $etapePrecedente) {
            if (!$etapesRealisees->contains($etapePrecedente->id)) {
                $this->addError('selectedEtapeId', 
                    "L'étape '{$etapePrecedente->nom}' doit être réalisée avant cette étape.");
                return false;
            }
        }

        return true;
    }

    /**
     * Supprimer une étape de traitement
     */
    public function supprimerEtape($suiviTraitementId)
    {
        $suiviTraitement = SuiviTraitement::find($suiviTraitementId);
        
        if ($suiviTraitement && $suiviTraitement->suivie_id === $this->suivie->id) {
            // Supprimer les fichiers associés
            if ($suiviTraitement->documents) {
                foreach ($suiviTraitement->documents as $document) {
                    Storage::disk('public')->delete($document['path']);
                }
            }
            
            $suiviTraitement->delete();
            $this->loadData();
            session()->flash('message', 'Étape supprimée avec succès.');
        }
    }

    /**
     * Réinitialiser le formulaire
     */
    public function resetForm()
    {
        $this->selectedEtapeId = null;
        $this->commentaire = '';
        $this->documents = [];
        $this->statut = 'en_cours';
        $this->resetErrorBag();
    }

    /**
     * Télécharger un document
     */
    public function downloadDocument($suiviTraitementId, $documentIndex)
    {
        $suiviTraitement = SuiviTraitement::find($suiviTraitementId);
        
        if ($suiviTraitement && $suiviTraitement->suivie_id === $this->suivie->id) {
            $document = $suiviTraitement->documents[$documentIndex] ?? null;
            
            if ($document && Storage::disk('public')->exists($document['path'])) {
                return Storage::disk('public')->download($document['path'], $document['name']);
            }
        }
        
        session()->flash('error', 'Document non trouvé.');
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        return view('livewire.suivie.traitement-suivie');
    }
}
