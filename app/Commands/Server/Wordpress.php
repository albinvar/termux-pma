<?php

namespace App\Commands\Server;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Wordpress extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:wordpress
							{--n}
							{--port=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start wordpress site';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->callSilently('settings:init');
        $this->setPort();
        $this->checkInstallations();
    }
    
    public function checkInstallations()
    {
        echo shell_exec("clear");
        $this->info("");
        $this->logo();
        $this->info("\n");
        $dir = config('wordpress.PATH');
        $cmd = "php -S 127.0.0.1:{$this->port} -t {$dir}";
        $this->comment("Starting wordpress at : http://127.0.0.1:{$this->port}");
        $this->info("");
        $this->launch();
        shell_exec($cmd);
    }
    
    public function logo()
    {
        $figlet = new \Laminas\Text\Figlet\Figlet();
        echo $figlet->setFont(config('logo.font'))->render(config('logo.name'));
    }
    
    private function setPort()
    {
        if (!empty($this->option('port'))) {
            $this->port = $this->option('port');
        } elseif (!empty($this->getPort())) {
            $this->port = $this->getPort();
        } else {
            $this->port = config('WORDPRESS_PORT');
        }
    }
    
    private function launch()
    {
        if (!$this->option('n')) {
            return shell_exec("xdg-open http://127.0.0.1:{$this->port}");
        }
    }
    
    public function getPort()
    {
        $json_object = file_get_contents(config('settings.PATH').'/settings.json');
        $data = json_decode($json_object, true);
        return $data['wordpress_port'];
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}