<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuthToken;

class DeleteRevokedTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-revoked-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired tokens from the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        date_default_timezone_set('Europe/Ljubljana');
        $current_time = time();
        $expired_token = AuthToken::where('expires_at', '>=',$current_time)->get();
        foreach ($expired_token as $token){
            $token->delete();
        }
    }
}
