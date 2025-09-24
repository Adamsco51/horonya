<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Contrôleur pour gérer les documents
 * Gère l'upload, le téléchargement et la suppression des documents
 */
class DocumentController extends Controller
{
    /**
     * Télécharger un document
     */
    public function download(Document $document)
    {
        // Vérifier que le fichier existe
        if (!$document->exists()) {
            abort(404, 'Fichier non trouvé');
        }

        // Vérifier les permissions (optionnel - à adapter selon vos besoins)
        // $this->authorize('view', $document);

        return Storage::download(
            $document->chemin,
            $document->nom_original,
            [
                'Content-Type' => $document->type_mime,
            ]
        );
    }

    /**
     * Afficher un document dans le navigateur (pour les images, PDF, etc.)
     */
    public function view(Document $document)
    {
        // Vérifier que le fichier existe
        if (!$document->exists()) {
            abort(404, 'Fichier non trouvé');
        }

        // Vérifier les permissions
        // $this->authorize('view', $document);

        $file = Storage::get($document->chemin);

        return response($file, 200, [
            'Content-Type' => $document->type_mime,
            'Content-Disposition' => 'inline; filename="' . $document->nom_original . '"',
        ]);
    }

    /**
     * Uploader un ou plusieurs documents
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB max par fichier
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
            'description' => 'nullable|string|max:500',
        ]);

        $documents = [];
        $files = $request->file('files', []);

        foreach ($files as $file) {
            $documents[] = $this->storeDocument(
                $file,
                $request->documentable_type,
                $request->documentable_id,
                $request->description
            );
        }

        return response()->json([
            'success' => true,
            'message' => count($documents) . ' document(s) uploadé(s) avec succès',
            'documents' => $documents,
        ]);
    }

    /**
     * Supprimer un document
     */
    public function destroy(Document $document)
    {
        // Vérifier les permissions
        // $this->authorize('delete', $document);

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document supprimé avec succès',
        ]);
    }

    /**
     * Stocker un document sur le disque et en base de données
     */
    private function storeDocument($file, string $documentableType, int $documentableId, ?string $description = null): Document
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Générer un nom unique pour le fichier
        $storageName = Str::uuid() . '.' . $extension;
        
        // Définir le chemin de stockage basé sur le type d'entité
        $directory = 'documents/' . strtolower(class_basename($documentableType));
        $path = $file->storeAs($directory, $storageName, 'local');

        // Créer l'enregistrement en base de données
        return Document::create([
            'nom_original' => $originalName,
            'nom_stockage' => $storageName,
            'chemin' => $path,
            'type_mime' => $mimeType,
            'taille' => $size,
            'extension' => $extension,
            'documentable_type' => $documentableType,
            'documentable_id' => $documentableId,
            'user_id' => Auth::id(),
            'description' => $description,
        ]);
    }

    /**
     * Lister les documents d'une entité
     */
    public function index(Request $request)
    {
        $request->validate([
            'documentable_type' => 'required|string',
            'documentable_id' => 'required|integer',
        ]);

        $documents = Document::where('documentable_type', $request->documentable_type)
            ->where('documentable_id', $request->documentable_id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'documents' => $documents,
        ]);
    }

    /**
     * Obtenir les informations d'un document
     */
    public function show(Document $document)
    {
        // Vérifier les permissions
        // $this->authorize('view', $document);

        return response()->json([
            'success' => true,
            'document' => $document->load('user'),
        ]);
    }
}
