<?php

namespace App\Console\Commands;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subDays(30);

        $count = User::onlyTrashed()
            ->where('deleted_at', '<=', $threshold)
            ->count();

        User::onlyTrashed()
            ->where('deleted_at', '<=', $threshold)
            ->forceDelete();

        $this->info("Purged $count soft-deleted records.");
    }
}
