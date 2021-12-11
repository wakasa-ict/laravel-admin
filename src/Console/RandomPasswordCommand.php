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
            $usernames[] = $this->ask('Please enter a username who needs to randomize his password');
        }else{
            $usernames = explode(',', $usernamesStr);

        }

        foreach ($usernames as $username) {
            $username = trim($username);
            $user = $userModel::query()->where('username', $username)->first();
    
            if (is_null($user)) {
                $this->error('The user you entered is not exists');
                goto askForUserName;
            }
    
            $password = $this->createPassword();
    
            $user->password = bcrypt($password);
            $user->save();
    
            $this->info("ID: {$user->username}\nPASSWORD: {$password}");
        }
    }

    public function createPassword(){

        $pwd = str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz0123456789!$%&()*+[]{}');
    
        $str = substr(str_shuffle($pwd), 0, 8);// 先頭８桁をランダムパスワードとして使う
    
        // 大文字小文字の英字と数字が混在するかどうかをチェック
        // 混在すれば、パスワードを返し
        if( preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!$%&()*+[]{}]).*$/',$str) ){ // コーディング量が少ない反面、読みづらい、理解しにくい正規表現
            return $str;
        }
        // 混在しなければ、もう一度再帰関数を呼び出し
        else{
            return $this->createPassword();
        }
    
    }
    
}
