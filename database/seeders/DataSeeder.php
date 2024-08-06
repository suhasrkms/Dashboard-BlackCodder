<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DataSeeder extends Seeder
{
    public function run()
    {
        $csvFile = public_path('data.csv');

        if (!file_exists($csvFile) || !is_readable($csvFile)) {
            $this->command->error('CSV file does not exist or is not readable.');
            return;
        }

        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0); // The first row contains the headers
        $records = $csv->getRecords();

        foreach ($records as $record) {
            DB::table('populate_data')->insert([
                'end_year' => $this->parseInteger($record['end_year']),
                'citylng' => $this->parseFloat($record['citylng']),
                'citylat' => $this->parseFloat($record['citylat']),
                'intensity' => $this->parseInteger($record['intensity']),
                'sector' => $record['sector'],
                'topic' => $record['topic'],
                'insight' => $record['insight'],
                'swot' => $record['swot'] ?: null,
                'url' => $record['url'],
                'region' => $record['region'] ?: null,
                'start_year' => $this->parseInteger($record['start_year']),
                'impact' => $this->parseInteger($record['impact']),
                'added' => $this->convertToMysqlDateTime($record['added']),
                'published' => $this->convertToMysqlDateTime($record['published']),
                'city' => $record['city'] ?: null,
                'country' => $record['country'] ?: null,
                'relevance' => $this->parseInteger($record['relevance']),
                'pestle' => $record['pestle'],
                'source' => $record['source'],
                'title' => $this->truncateText($record['title']),
                'likelihood' => $this->parseInteger($record['likelihood']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function parseInteger($value)
    {
        return is_numeric($value) ? (int)$value : null;
    }

    private function parseFloat($value)
    {
        return is_numeric($value) ? (float)$value : null;
    }

    private function convertToMysqlDateTime($dateString)
    {
        if (!$dateString) {
            return null;
        }

        $formats = [
            'F j, Y g:i:s A',
            'F j, Y H:i:s',
            'Y-m-d H:i:s',
        ];

        foreach ($formats as $format) {
            try {
                $parsedDate = \Carbon\Carbon::createFromFormat($format, $dateString);
                return $parsedDate->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                // Continue to the next format
            }
        }

        return null;
    }

    private function truncateText($text, $maxLength = 255)
    {
        return substr($text, 0, $maxLength);
    }
}
