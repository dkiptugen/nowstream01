<?php

namespace App\Console\Commands;

use App\Models\Language;
use Illuminate\Console\Command;

class LanguageImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'language:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lang        =   file_get_contents(__DIR__."/Language.json");
        $languages    =   json_decode($lang);
        foreach ($languages as $language)
            {
                Language::updateOrCreate(['code'=>$language->code],['name'=>$language->name,'native_name'=>$language->nativeName]);
            }
        return Command::SUCCESS;
    }
}
