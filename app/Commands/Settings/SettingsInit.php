<?php

declare(strict_types=1);

namespace App\Commands\Settings;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Helpers\Settings\Handler;

class SettingsInit extends Command
{
    public $settings;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'settings:init
							{--f|--force}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Init Settings Json file';

    public function __construct()
    {
        parent::__construct();
        $this->settings = new Handler();
        $this->path = config('settings.PATH');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('force')) {
            if ($this->confirm('Do you want to reset your webzone settings?')) {
                $this->settings->setStrictMode(true);
            }
        } else {
            $this->checkIfSettingsExist();
        }
    }

    public function create()
    {
        $this->settings->init();
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

}
