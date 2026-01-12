<?php

namespace App\Console\Commands;

use App\Models\License;
use App\Notifications\PaymentCreatedNotification;
use Illuminate\Console\Command;

class CheckLicenseExpiration extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'licenses:check-expiration';

    /**
     * The console command description.
     */
    protected $description = 'Check license expiration dates and update renewal/billing status accordingly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking license expiration dates and updating statuses...');

        // Get all licenses with expiration dates to check
        $licenses = License::whereNotNull('expiration_date')->get();

        $renewalOpened = 0;
        $renewalExpired = 0;
        $renewalClosed = 0;

        foreach ($licenses as $license) {
            $oldRenewalStatus = $license->renewal_status;
            
            // Update renewal and billing status based on expiration date
            $license->updateRenewalBillingStatus();
            
            // Refresh to get updated values
            $license->refresh();

            if ($license->renewal_status !== $oldRenewalStatus) {
                if ($license->renewal_status === License::RENEWAL_OPEN) {
                    $renewalOpened++;
                    $this->line("License #{$license->id} ({$license->transaction_id}) - Renewal window OPENED.");
                } elseif ($license->renewal_status === License::RENEWAL_EXPIRED) {
                    $renewalExpired++;
                    $this->line("License #{$license->id} ({$license->transaction_id}) - EXPIRED (can still renew).");
                } else {
                    $renewalClosed++;
                    $this->line("License #{$license->id} ({$license->transaction_id}) - Renewal window CLOSED.");
                }
            }
        }

        $this->info("Completed:");
        $this->info("  - {$renewalOpened} licenses entered renewal window (billing open)");
        $this->info("  - {$renewalExpired} licenses expired (can still renew)");
        $this->info("  - {$renewalClosed} licenses closed (more than 2 months until expiry)");

        return Command::SUCCESS;
    }
}

