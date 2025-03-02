<?php

namespace App\Services;

use App\Http\Crud\Service\CrudService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class GoogleSheetService {
    private \Google_Service_Sheets $service;
    private const COMMENT_NAME = 'Your Comment';

    public function __construct() {
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets API');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(base_path('credentials.json'));

        $this->service = new \Google_Service_Sheets($client);
    }

    private function getSpreadsheetId(string $crudServiceClass): ?string {
        $url = DB::table('google_sheets')
            ->where('crud_service', $crudServiceClass)
            ->pluck('url')
            ->first();

        if (!$url) {
            return null;
        }

        if (!preg_match('/\\/spreadsheets\\/d\\/([^\\/]+)\\//', $url, $matches)) {
            Log::error('Invalid Google Sheet url', [$crudServiceClass, $url]);
            return null;
        }

        return $matches[1];
    }

    private function getNameFromNumber(int $num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intdiv($num, 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

    public function getIdAndCommentColumns(string $spreadsheetId): array {
        $result = ['', ''];

        $spreadsheet = $this->service->spreadsheets->get($spreadsheetId);
        $properties = $spreadsheet->getSheets()[0]->getProperties();
        $title = $properties->title;
        $gridProperties = $properties->getGridProperties();

        $colCount = $gridProperties->getColumnCount();

        if ($gridProperties->getRowCount() < 1 || $colCount < 2) {
            return $result;
        }

        $lastColumName = $this->getNameFromNumber($colCount - 1);
        $range = "$title!A1:{$lastColumName}1";
        $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        if (!$values) {
            return $result;
        }

        $idColumn = -1;
        $commentColumn = -1;

        foreach ($values[0] as $i => $column) {
            if ($column == 'id') {
                if ($idColumn < 0) {
                    $idColumn = $i;
                }
            } elseif ($column == self::COMMENT_NAME) {
                $commentColumn = $i;
            }
        }

        if ($idColumn < 0 || $commentColumn < 0) {
            return $result;
        }

        return [$this->getNameFromNumber($idColumn), $this->getNameFromNumber($commentColumn)];
    }

    public function getComments(string $spreadsheetId, int $limit = 0): array {
        [$idColumn, $commentColum] = $this->getIdAndCommentColumns($spreadsheetId);

        if (!$idColumn || !$commentColum) {
            return [];
        }

        $spreadsheet = $this->service->spreadsheets->get($spreadsheetId);
        $properties = $spreadsheet->getSheets()[0]->getProperties();
        $title = $properties->title;
        $gridProperties = $properties->getGridProperties();

        $rowCount = $gridProperties->getRowCount() - 1;

        if ($rowCount < 1) {
            return [];
        }

        $rowCount++;

        $range = "$title!{$idColumn}2:$idColumn$rowCount";
        $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
        $idValues = $response->getValues();

        if (!$idValues) {
            return [];
        }

        $range = "$title!{$commentColum}2:$commentColum$rowCount";
        $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
        $commentValues = $response->getValues();

        if (!$commentValues) {
            return [];
        }

        $result = [];

        if ($limit < 1) {
            $limit = count($idValues);
        }

        foreach ($idValues as $i => $idRow) {
            $id = trim(strval($idRow[0] ?? ''));
            if (!strlen($id)) {
                break;
            }

            $comment = trim(strval($commentValues[$i][0] ?? ''));
            if (strlen($comment)) {
                $result[intval($id)] = $comment;

                if (count($result) >= $limit) {
                    break;
                }
            }
        }

        return $result;
    }

    public function upload(string $crudServiceClass) {
        $spreadsheetId = $this->getSpreadsheetId($crudServiceClass);
        if (!$spreadsheetId) {
            return;
        }

        $comments = $this->getComments($spreadsheetId);

        $spreadsheet = $this->service->spreadsheets->get($spreadsheetId);
        $properties = $spreadsheet->getSheets()[0]->getProperties();
        $title = $properties->title;

        $clear = new \Google_Service_Sheets_ClearValuesRequest();
        $this->service->spreadsheets_values->clear($spreadsheetId, $title, $clear);

        /** @var CrudService $crudService */
        $crudService = app($crudServiceClass);

        $columns = Schema::getColumnListing($crudService->makeEmptyModel()->getTable());

        $columns = array_values(array_filter($columns, function ($column) {
            return $column != 'status' && $column != 'deleted_at';
        }));

        $rows = [array_merge($columns, [self::COMMENT_NAME])];

        $data = $crudService->makeQueryForListing()->allowed()->orderBy('id')->get()->toArray();
        foreach ($data as $srcRow) {
            $row = [];
            foreach ($columns as $column) {
                $row[] = strval($srcRow[$column] ?? '');
            }
            $row[] = $comments[$srcRow['id']] ?? null;
            $rows[] = $row;
        }

        $valueRange = new \Google_Service_Sheets_ValueRange();
        $valueRange->setValues($rows);

        $options = ['valueInputOption' => 'USER_ENTERED'];
        $this->service->spreadsheets_values->update($spreadsheetId, $title, $valueRange, $options);
    }
}
