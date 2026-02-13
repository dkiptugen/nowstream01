<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportRadio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:radio';
    protected $base_url   = 'https://www.songaplay.com/engine/api/';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
            $request = Http::withHeaders(['appkey'=>'h3jDn575SEVAhZWSFK8bIP7j2TJ2cCtf','Host'=>'https://www.songaplay.com'])
                //->withoutVerifying()
            ->post($this->base_url.'v2/get_radio',['start'=>0,'end'=>100,'orderBy'=>'id','orderFormat'=>'desc']);
            dd($request->body());
    }
}
