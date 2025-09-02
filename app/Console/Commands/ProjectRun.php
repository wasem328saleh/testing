<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command init Project';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('=====================Task 1: Composer update=====================');
        if ($this->confirm('Do you want to update Composer dependencies?')) {
            exec('composer update --ignore-platform-req=ext-sodium');
            sleep(1);

        } else {
            $this->info('Composer update skipped.');
        }
        $this->info('====================Task 2: Copying .env file...======================');
        $envFilePath = base_path('.env');
        $exampleEnvFilePath = base_path('.env.example');

        if (!File::exists($envFilePath)) {
            File::copy($exampleEnvFilePath, $envFilePath);
        }
        $this->assgineVlue();
        $this->call('config:clear');
        sleep(2);

        $this->info('=====================Task 3: migrate fresh and seeder=====================');

        $this->call('migrate:fresh');
        $this->call('db:seed');
        sleep(1);

        $this->info('=====================Task 4 : Installing Passport=====================');
        $this->call('passport:install');
        sleep(1);

        $this->info('=====================Task 5: Generating application key=====================');
        $this->call('key:generate');
        sleep(1);

        $this->info('=====================Starting Project=====================');

        $this->call('serve');
    }

    public function assgineVlue(): void
    {
        $this->info('Assigning values to .env file...');
        $envFilePath = base_path('.env');
        $envValues = [

            'APP_NAME'=>'Signature-Group',
            'DB_CONNECTION' => 'mysql',
            'DB_DATABASE' => 'signature_group_db',
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => 'smtp.gmail.com',
            'MAIL_PORT' => 587,
            'MAIL_USERNAME' => 'wasem.saleh328@gmail.com',
            'MAIL_PASSWORD' => 'bjgoygqekfobgspd',
            'MAIL_ENCRYPTION' => 'tls',
            'MAIL_FROM_ADDRESS' => "wasem.saleh328@gmail.com",
            'MAIL_FROM_NAME'=>'"${APP_NAME}"'
        ];
        $envFile = File::get($envFilePath);

        foreach ($envValues as $key => $value) {
            $envFile = preg_replace("/^$key=.*$/m", "$key=$value", $envFile);
        }

        File::put($envFilePath, $envFile);

        $this->info('Values assigned successfully.');
    }
}
