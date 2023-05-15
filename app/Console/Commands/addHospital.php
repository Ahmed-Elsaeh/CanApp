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
    protected $signature = 'addhospital {name?} {location?} {image?} {user_id?}';

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
        $this->ask("What's the name you would like to insert");
        $hospital->name = $this->argument('name');
        $this->ask("What's the location you would like to insert");
        $hospital->location = $this->argument('location');
        $this->ask("What's the image you would like to insert");
        $hospital->image = $this->argument('image');
        $hospital->user_id = $this->argument('user_id');
        $hospital->save();
        $this->info('command successful!');
    }
}
