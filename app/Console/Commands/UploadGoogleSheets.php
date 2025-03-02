<?php

namespace App\Console\Commands;

use App\Http\Crud\Service\CrudServicesManager;
use App\Services\GoogleSheetService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class UploadGoogleSheets extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-google-sheets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload Google Sheets';

    /**
     * Execute the console command.
     */
    public function handle(GoogleSheetService $service, CrudServicesManager $crudManager) {
        foreach ($crudManager->getCrudServicesClasses() as $crudServiceClass) {
            try {
                $service->upload($crudServiceClass);
            } catch (Throwable $e) {
                Log::error('Cannot upload Google Sheet', [$crudServiceClass, $e]);
            }
        }
    }
}
