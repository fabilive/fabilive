<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FabiliveResetCommand extends Command
{
    protected $signature = 'fabilive:reset
                            {--force : Skip confirmation prompt}
                            {--i-understand : Acknowledge data loss}';

    protected $description = 'Reset all transactional data to zero state for go-live. Preserves admin accounts and system settings.';

    /**
     * Tables to truncate in FK-safe order.
     */
    private array $tablesToReset = [
        // Notifications and logs first (leaf tables)
        'notification_logs',
        'user_notifications',

        // Chat and delivery
        'admin_user_messages',
        'admin_user_conversations',
        'messages',
        'live_messages',
        'delivery_job_events',
        'delivery_job_stops',
        'delivery_chat_threads',
        'delivery_riders',
        'delivery_jobs',

        // Financial
        'wallet_ledger',
        'referral_usages',
        'referral_codes',
        'complaints',
        'seller_likes',
        'notification_preferences',

        // Orders and transactions
        'order_tracks',
        'vendor_orders',
        'transactions',
        'deposits',
        'withdraws',
        'payout_requests',

        // Products and interactions
        'ratings',
        'wishlists',
        'compares',
        'product_clicks',
        'carts',

        // Users (but NOT admins or system settings)
        'notifications',
    ];

    public function handle(): int
    {
        if (!$this->option('force') || !$this->option('i-understand')) {
            $this->error('This command will DELETE ALL transactional data.');
            $this->error('Run with: php artisan fabilive:reset --force --i-understand');
            return 1;
        }

        $this->warn('⚠️  FABILIVE DATA RESET - This will truncate all transactional data.');
        $this->info('Admin accounts and system settings will be preserved.');

        if (!$this->option('force')) {
            if (!$this->confirm('Are you absolutely sure?')) {
                $this->info('Aborted.');
                return 0;
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $reset = 0;
        $skipped = 0;

        foreach ($this->tablesToReset as $table) {
            if (Schema::hasTable($table)) {
                try {
                    DB::table($table)->truncate();
                    $this->line("  ✓ Truncated: {$table}");
                    $reset++;
                } catch (\Exception $e) {
                    $this->warn("  ✗ Failed: {$table} — {$e->getMessage()}");
                    $skipped++;
                }
            } else {
                $skipped++;
            }
        }

        // Reset user balances but keep accounts
        DB::table('users')->update([
            'current_balance' => 0,
            'reward' => 0,
        ]);
        $this->line("  ✓ Reset user balances to 0");

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->newLine();
        $this->info("✅ Reset complete: {$reset} tables truncated, {$skipped} skipped.");
        $this->info("Admin accounts, products, categories, and system settings are preserved.");

        return 0;
    }
}
