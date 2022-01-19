<?php

namespace App\Console\Commands;

use App\Models\Shop;
use App\Services\ManningResolver;
use Illuminate\Console\Command;

class FunHouse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'funhouse:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var ManningResolver
     */
    private $manningResolver;

    /**
     * FunHouse constructor.
     *
     * @param  ManningResolver  $manningResolver
     */
    public function __construct(ManningResolver $manningResolver)
    {
        parent::__construct();

        $this->manningResolver = $manningResolver;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Building DTO");
        $shop = Shop::with('staff', 'rotas.shifts')->get()->first();

        $dto = $this->manningResolver->resolve($shop->rotas->first());

        dump($dto);

        $this->info("Finished");
    }
}
