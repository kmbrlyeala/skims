<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SimulateOrderProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:progress';

    protected $description = 'Automatically simulate order delivery progress for demonstration.';

    public function handle()
    {
        // Shipped -> Delivered
        $shipped = \App\Models\Order::where('status', 'shipped')->update(['status' => 'delivered']);
        
        // Processing -> Shipped
        $processing = \App\Models\Order::where('status', 'processing')->update(['status' => 'shipped']);
        
        // Pending -> Processing
        $pending = \App\Models\Order::where('status', 'pending')->update(['status' => 'processing']);
        
        $this->info("Simulated Order Progress: $pending packed, $processing shipped, $shipped delivered.");
    }
}
