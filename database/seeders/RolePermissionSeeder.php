<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Réinitialiser le cache des rôles et permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions de base
        $permissions = [
            // Permissions générales
            'view_dashboard',
            'view_suivies',
            'create_suivies',
            'edit_suivies',
            'delete_suivies',
            
            // Permissions BL (Bills of Lading)
            'view_bl',
            'create_bl',
            'edit_bl',
            'delete_bl',
            
            // Permissions Clients
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            
            // Permissions Types de Travail
            'view_type_travail',
            'create_type_travail',
            'edit_type_travail',
            'delete_type_travail',
            
            // Permissions Documents
            'view_documents',
            'upload_documents',
            'download_documents',
            'delete_documents',
            
            // Permissions financières (montants)
            'view_financial_data',
            
            // Permissions administration
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'activate_users',
            'view_admin_console',
            
            // Permissions utilisateurs détaillées
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_user_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $agentTransit = Role::firstOrCreate(['name' => 'agent transit']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $comptable = Role::firstOrCreate(['name' => 'comptable']);

        // Assigner les permissions aux rôles
        
        // Agent Transit (rôle par défaut) - Lecture et création uniquement
        $agentTransit->givePermissionTo([
            'view_dashboard',
            'view_suivies',
            'create_suivies',
            'view_bl',
            'create_bl',
            'view_clients',
            'create_clients',
            'view_type_travail',
            'view_documents',
            'upload_documents',
            'download_documents',
        ]);

        // Manager - Toutes les permissions sauf administration système
        $manager->givePermissionTo([
            'view_dashboard',
            'view_suivies',
            'create_suivies',
            'edit_suivies',
            'delete_suivies',
            'view_bl',
            'create_bl',
            'edit_bl',
            'delete_bl',
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            'view_type_travail',
            'create_type_travail',
            'edit_type_travail',
            'delete_type_travail',
            'view_documents',
            'upload_documents',
            'download_documents',
            'delete_documents',
            'view_financial_data',
            'manage_users',
            'activate_users',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_user_logs',
        ]);

        // Comptable - Permissions de base + données financières
        $comptable->givePermissionTo([
            'view_dashboard',
            'view_suivies',
            'create_suivies',
            'view_bl',
            'create_bl',
            'view_clients',
            'create_clients',
            'view_type_travail',
            'view_documents',
            'upload_documents',
            'download_documents',
            'view_financial_data',
        ]);

        // Admin - Toutes les permissions
        $admin->givePermissionTo(Permission::all());

        // Créer un utilisateur admin par défaut
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@htt.com'],
            [
                'name' => 'Administrateur',
                'password' => bcrypt('Admin0769@'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $adminUser->assignRole('admin');

        $this->command->info('Rôles et permissions créés avec succès!');
        $this->command->info('Utilisateur admin créé: admin@htt.com / Admin0769@');
    }
}
