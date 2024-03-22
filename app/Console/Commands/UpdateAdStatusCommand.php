<?php

namespace App\Console\Commands;

use App\Models\Ad;
use Illuminate\Console\Command;
use App\Http\Controllers\Api\SubscriptionController;

class UpdateAdStatusCommand extends Command
{
    protected $signature = 'subscriptions:check';
    protected $description = 'Update subscription of ads after three days';

    public function handle()
    {
        $controller = new SubscriptionController();
        $controller->show();
    }
}

