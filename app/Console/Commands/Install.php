<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('DB settings:');
        $params['DB_HOST'] = $this->ask('Host', 'localhost');
        $params['DB_PORT'] = $this->ask('Port', 3306);
        $params['DB_DATABASE'] = $this->ask('Data base name', 'homestead');
        $params['DB_USERNAME'] = $this->ask('User name', 'homestead');
        $params['DB_PASSWORD'] = $this->secret('Password');

        $this->info('E-mail settings:');
        $params['MAIL_HOST'] = $this->ask('Host', 'smtp.yandex.ru');
        $params['MAIL_PORT'] = $this->ask('Port', 465);
        $params['MAIL_USERNAME'] = $this->ask('User name', 'admin@admin.org');
        $params['MAIL_PASSWORD'] = $this->secret('Password');
        $params['MAIL_ENCRYPTION'] = $this->ask('Encryption', 'ssl');

        $config = file_get_contents(base_path('.env.example'));

        foreach ($params as $key=>$param)
            $config = preg_replace('/^'.$key.'=(.*)$/m', $key.'='.$param, $config);
        file_put_contents(base_path('.env'), $config);
        $this->info('Config file created');
        $this->call('key:generate');
        $this->call('migrate:refresh', [
            '--seed'=>true,
        ]);
    }
}
