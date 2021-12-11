<?php

namespace Encore\Admin\Console;

use Illuminate\Console\Command;

class RandomPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:random-password {usernames?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ramdomize password for a specific admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userModel = config('admin.database.users_model');
        $usernamesStr = $this->argument('usernames');

        if (empty($usernamesStr)) {
            askForUserName:
            $username = $this->ask('Please enter a username who needs to randomize his password');
            $username = trim($username);
            $user = $userModel::query()->where('username', $username)->first();
            if (is_null($user)) {
                $this->error('The user you entered is not exists');
                goto askForUserName;
            }
            $usernames = array($username);
        }else{
            $usernames = explode(',', $usernamesStr);

        }

        foreach ($usernames as $username) {
            $username = trim($username);
            $user = $userModel::query()->where('username', $username)->first();

            if (is_null($user)) {
                $this->error("The user: {$username} is not exists");
            }else{
                $password = $this->createPassword();

                $user->password = bcrypt($password);
                $user->save();

                $this->info("ID: {$user->username}\nPASSWORD: {$password}");
            }

        }
    }

    public function createPassword(){

        $collectionA = array_rand(array_flip(range('a', 'z')), 3);
        $collectionB = array_rand(array_flip(range('A', 'Z')), 2);
        $collectionC = array_rand(array_flip(range(1, 9)), 2);
        $collectionD = [array_rand(array_flip(['!','$','%','&','(',')','*','+','/']), 1)];
        $passwordstr = array_merge($collectionA,$collectionB,$collectionC,$collectionD);
        return str_shuffle(implode($passwordstr));
    }

}
