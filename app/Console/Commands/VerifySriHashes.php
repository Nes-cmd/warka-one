<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CspHashGenerator;

class VerifySriHashes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:verify-sri {--update : Update hashes if they differ}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify SRI hashes for external CDN resources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔒 Verifying SRI hashes for external resources...');
        $this->newLine();

        $sriHashes = CspHashGenerator::getSriHashes();
        $hasErrors = false;

        foreach ($sriHashes as $name => $config) {
            $this->info("Checking {$name}...");
            
            try {
                $currentHash = CspHashGenerator::generateSriHash($config['url']);
                $expectedHash = $config['integrity'];

                if ($currentHash === $expectedHash) {
                    $this->info("✅ {$name}: Hash matches");
                } else {
                    $hasErrors = true;
                    $this->error("❌ {$name}: Hash mismatch!");
                    $this->line("   URL: {$config['url']}");
                    $this->line("   Expected: {$expectedHash}");
                    $this->line("   Current:  {$currentHash}");
                    
                    if ($this->option('update')) {
                        $this->warn("   🔄 Use --update flag to see updated hash values");
                    }
                }
            } catch (\Exception $e) {
                $hasErrors = true;
                $this->error("❌ {$name}: Failed to fetch - {$e->getMessage()}");
            }
            
            $this->newLine();
        }

        if ($hasErrors) {
            $this->newLine();
            $this->warn('⚠️  Some SRI hashes need attention!');
            $this->line('If external resources have been updated, you need to:');
            $this->line('1. Update the hashes in app/Services/CspHashGenerator.php');
            $this->line('2. Update the integrity attributes in your Blade templates');
            return 1;
        } else {
            $this->info('🎉 All SRI hashes are valid!');
            return 0;
        }
    }
}
