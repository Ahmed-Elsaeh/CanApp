<?php

namespace App\Console\Commands;

use App\Models\Feed;
use Illuminate\Console\Command;

class addPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addpost {title} {body}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add post';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $post = new Feed();
        $post->title = $this->argument('title');
        $post->body= $this->argument('body');
        $post->save();
        $this->info('command successful!');
    }
}
