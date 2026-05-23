<?php

namespace App\Console\Commands;

use App\Models\Auth\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearDummyData extends Command
{
    protected $signature = 'data:clear-dummy
                            {--admin-email= : Keep this admin user email}
                            {--dry-run : Show what would be removed without deleting}
                            {--force : Execute without confirmation}';

    protected $description = 'Remove sample/transactional data and keep only admin login + core defaults';

    public function handle(): int
    {
        $adminEmail = (string) ($this->option('admin-email') ?: 'studio@notjustweb.com');
        $isDryRun = (bool) $this->option('dry-run');

        $admin = User::where('email', $adminEmail)->first();

        if (! $admin) {
            $this->error('Admin user not found: ' . $adminEmail);

            return self::FAILURE;
        }

        if (! $isDryRun && ! $this->option('force')) {
            $ok = $this->confirm('This will permanently remove sample and transactional data. Continue?', false);

            if (! $ok) {
                $this->warn('Aborted.');

                return self::SUCCESS;
            }
        }

        $tables = [
            // Current document pipeline
            'document_item_taxes',
            'document_items',
            'document_totals',
            'document_histories',
            'documents',

            // Banking and reconciliation data
            'transaction_taxes',
            'transactions',
            'transfers',
            'reconciliations',
            'recurring',

            // Master data often used in sample datasets
            'item_taxes',
            'items',
            'contact_persons',
            'reports',
            'notifications',
            'firewall_logs',

            // Legacy v1 pipeline tables (if present)
            'invoice_item_taxes',
            'invoice_items',
            'invoice_totals',
            'invoice_histories',
            'invoices',
            'bill_item_taxes',
            'bill_items',
            'bill_totals',
            'bill_histories',
            'bills',
        ];

        $summary = [];

        DB::transaction(function () use ($tables, $admin, $isDryRun, &$summary): void {
            Schema::disableForeignKeyConstraints();

            foreach ($tables as $table) {
                if (! Schema::hasTable($table)) {
                    continue;
                }

                $count = DB::table($table)->count();
                $summary[$table] = $count;

                if (! $isDryRun && $count > 0) {
                    DB::table($table)->delete();
                }
            }

            // Keep only admin-linked contact, remove customer/vendor sample contacts.
            if (Schema::hasTable('contacts')) {
                $contactDelete = DB::table('contacts')
                    ->whereNull('user_id')
                    ->orWhere('user_id', '!=', $admin->id);

                $summary['contacts'] = $contactDelete->count();

                if (! $isDryRun) {
                    $contactDelete->delete();
                }
            }

            // Keep only selected admin user.
            if (Schema::hasTable('users')) {
                $userDelete = DB::table('users')->where('id', '!=', $admin->id);
                $summary['users'] = $userDelete->count();

                if (! $isDryRun) {
                    $userDelete->delete();
                }
            }

            if (Schema::hasTable('user_roles')) {
                $userRoleDelete = DB::table('user_roles')->where('user_id', '!=', $admin->id);
                $summary['user_roles'] = $userRoleDelete->count();

                if (! $isDryRun) {
                    $userRoleDelete->delete();
                }
            }

            if (Schema::hasTable('user_permissions')) {
                $userPermissionDelete = DB::table('user_permissions')->where('user_id', '!=', $admin->id);
                $summary['user_permissions'] = $userPermissionDelete->count();

                if (! $isDryRun) {
                    $userPermissionDelete->delete();
                }
            }

            if (Schema::hasTable('user_companies')) {
                $userCompanyDelete = DB::table('user_companies')->where('user_id', '!=', $admin->id);
                $summary['user_companies'] = $userCompanyDelete->count();

                if (! $isDryRun) {
                    $userCompanyDelete->delete();
                }
            }

            if (Schema::hasTable('user_dashboards')) {
                $userDashboardDelete = DB::table('user_dashboards')->where('user_id', '!=', $admin->id);
                $summary['user_dashboards'] = $userDashboardDelete->count();

                if (! $isDryRun) {
                    $userDashboardDelete->delete();
                }
            }

            if (Schema::hasTable('user_invitations')) {
                $userInvitationDelete = DB::table('user_invitations')->where('user_id', '!=', $admin->id);
                $summary['user_invitations'] = $userInvitationDelete->count();

                if (! $isDryRun) {
                    $userInvitationDelete->delete();
                }
            }

            Schema::enableForeignKeyConstraints();
        });

        $mode = $isDryRun ? 'DRY RUN' : 'DONE';
        $this->info('[' . $mode . '] Sample data cleanup summary:');

        foreach ($summary as $table => $count) {
            $this->line('- ' . $table . ': ' . $count);
        }

        if (! $isDryRun) {
            $this->info('Only admin login retained: ' . $admin->email);
        }

        return self::SUCCESS;
    }
}
