<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PurgeExpiredSecrets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-expired-secrets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans all expired secret messages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = \App\Models\Secret::where('expires_at', '<', now())->delete();

        $this->info("Очистка завершена. Удалено записей: {$deletedCount}");
    }
}
