<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class ArtisanCommandRunner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 86400;

    private $command;

    private $cwd;

    /**
     * Create a new job instance.
     *
     * @param $command
     */
    public function __construct($command)
    {
        $this->cwd = env('PROCESS_CWD');
        $this->command = implode(" ", array(env('PHP_ARTISAN_PATH'), $command));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = new Process($this->command, $this->cwd);
        $process->setTimeout($this->timeout);
        $process->run();
    }
}
