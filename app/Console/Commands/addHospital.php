<?php

namespace App\Console\Commands;

use App\Models\Hospital;
use Illuminate\Console\Command;

class addHospital extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addhospital';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add a hospital';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $hospital = new Hospital();
        $name = $this->ask("What's the name you would like to insert");
        $hospital->name = $name;
        $location = $this->ask("What's the location you would like to insert");
        $hospital->location = $location;
        $image = $this->ask("What's the image you would like to insert");
        $hospital->image = $image;
        // $this->info($hospital);
        $hospital->save();
        $this->info('command successful!');
    }
}
