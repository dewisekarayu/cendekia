<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MarkMigrationsRan extends Command
{
    protected $signature = 'migrate:mark-ran {migrations*}';
    protected $description = 'Mark specific migrations as ran without executing them';

    public function handle(): int
    {
        $batch = DB::table('migrations')->max('batch') + 1;

        foreach ($this->argument('migrations') as $migration) {
            $exists = DB::table('migrations')->where('migration', $migration)->exists();
            if ($exists) {
                $this->line("Already marked: {$migration}");
            } else {
                DB::table('migrations')->insert(['migration' => $migration, 'batch' => $batch]);
                $this->info("Marked as ran: {$migration}");
            }
        }

        return 0;
    }
}
