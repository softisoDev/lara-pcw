<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ClearTempSearch extends Command
{

    protected $tempSearchDir;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datafiniti:clear-search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear temporary search file that has been created 1 week before';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->tempSearchDir = env('SEARCH_DIR') . DIRECTORY_SEPARATOR . env('TEMP_SEARCH_DIR');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = Storage::disk('public')->files($this->tempSearchDir);

        foreach ($files as $file) {

            $orgFile = $file;

            if (strpos($file, "_")) {

                $file = explode('_', basename($file, '.json'));

                if (key_exists(1, $file)) {

                    $createdTime = Carbon::createFromTimestamp($file[1]);
                    $now = Carbon::now();
                    $difference = $now->diffInDays($createdTime);

                    if ($difference > 1) {
                        try {
                            Storage::disk('public')->delete($orgFile);
                            echo sprintf("%s deleted\n\r", basename($orgFile));
                        } catch (\Exception $e) {
                            \Log::error($e->getMessage());
                            echo sprintf("%s can't be deleted\n\r", basename($orgFile));
                        }
                    }
                }
            }
        }

        $this->info('Temporary search files were cleared successfully');
    }
}
