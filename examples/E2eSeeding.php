<?php

#namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\BufferedOutput;

class E2eSeeding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'e2e:seeding';
    protected bool $log = true;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = microtime(true);
//        $this->callSilent('db:wipe', [
//            '--drop-views' => false,
//            '--drop-types' => false,
//        ]);

        $database = null;
        $this->laravel['db']->connection($database)
            ->getSchemaBuilder()
            ->dropAllTables();

        $drop = microtime(true) - $start;
        $this->log('Drop: ' . $drop);

        $this->callSilent('migrate', [
            '--path' => [
//                'vendor/laravel/passport/database/migrations',
                'database/migrations'
            ]
        ]);

        $migrate = microtime(true) - $start - $drop;
        $this->log('Migrate: ' . $migrate);

//        $this->call('db:seed');
//        , [
//            '--class' => 'TestDatabaseSeeder' // TODO move to arg
//        ]);

        $seeding = microtime(true) - $start - $drop - $migrate;
        $this->log('Seeding: ' . $seeding);
    }

    private function log($message)
    {
        if ($this->log) {
            Log::info($message);
            $this->info($message);
        }
    }
}
