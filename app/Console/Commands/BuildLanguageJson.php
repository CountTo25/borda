<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Js;

class BuildLanguageJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:lang';

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
     * @return int
     */
    public function handle()
    {
        $lang = config('lang');
        $json = json_encode($lang, JSON_UNESCAPED_UNICODE);

        $file = fopen('svelte/src/lang.json', 'w');
        fwrite($file, $json);
        fclose($file);

        return Command::SUCCESS;
    }
}
