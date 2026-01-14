<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class MigrateStockQuantityToMovements extends Command
{
    protected $signature = 'inventory:migrate-stock-quantity';
    protected $description = 'Convert existing stock_quantity values to stock movements for all products';

    public function handle()
    {
        $bar = $this->output->createProgressBar(Product::count());
        $bar->start();
        DB::transaction(function () use ($bar) {
            foreach (Product::all() as $product) {
                if ($product->stock_quantity > 0 && $product->stockMovements()->count() == 0) {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $product->stock_quantity,
                        'reason' => 'Initial stock migration',
                        'user_id' => null,
                    ]);
                }
                $bar->advance();
            }
        });
        $bar->finish();
        $this->info("\nStock migration complete.");
    }
}
