<?php

namespace App\Console\Commands;

use App\Http\Crud\Core\DemoCrudService;
use App\Services\GoogleSheetService;
use Illuminate\Console\Command;

class FetchGoogleSheetComments extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-google-sheet-comments {count?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Google Sheet comments';

    /**
     * Execute the console command.
     */
    public function handle(GoogleSheetService $service) {
        $spreadsheetId = $service->getSpreadsheetId(DemoCrudService::class);
        if (!$spreadsheetId) {
            $this->fail('Invalid Google Sheet url');
        }

        $comments = $service->getComments($spreadsheetId, intval($this->argument('count')), $this->output);

        $this->newLine();

        foreach ($comments as $id => $comment) {
            $this->line($id . ' / ' . $comment);
        }
    }
}
