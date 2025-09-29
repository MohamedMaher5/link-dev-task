<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduled command to clean expired or unconfirmed bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expired_bookings = Booking::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subDays())
            ->delete();

        $this->info("Deleted {$expired_bookings} pending bookings older than 2 days.");

        return Command::SUCCESS;
    }
}
