<?php

namespace App\Console\Commands;

use App\Models\Hospital;
use Illuminate\Console\Command;

class deleteHospital extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deletehospital {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete a hospital by id';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $hospital = Hospital::where('id' , $this->argument('id'))->first()->delete();
        $this->info('command successful!');
    }
}
