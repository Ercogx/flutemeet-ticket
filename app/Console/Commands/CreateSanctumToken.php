<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateSanctumToken extends Command
{
    protected $signature = 'sanctum:token {user_id} {name=api-token}';

    protected $description = 'Create a new Sanctum token for a specific user';

    public function handle(): int
    {
        $user = User::find($this->argument('user_id'));

        if (! $user) {
            $this->error('User not found.');
            return Command::FAILURE;
        }

        $token = $user->createToken($this->argument('name'))->plainTextToken;

        $this->info('Token created successfully:');

        $this->line($token);

        return Command::SUCCESS;
    }
}
