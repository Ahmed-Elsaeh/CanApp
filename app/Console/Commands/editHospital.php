<?php

namespace App\Console\Commands;

use App\Models\Hospital;
use Illuminate\Console\Command;

class editHospital extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'editHospital {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'edit hospital info by id';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $hospital = Hospital::where('id' , $this->argument('id'))->first();
        $name = $this->ask("What's the name you would like to insert");
        $hospital->name = $name;
        $location = $this->ask("What's the location you would like to insert");
        $hospital->location = $location;
        $image = $this->ask("What's the image you would like to insert");
        $hospital->image = $image;
        $hospital->save();
    }
}
