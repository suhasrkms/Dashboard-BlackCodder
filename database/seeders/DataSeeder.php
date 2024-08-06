<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = public_path('data.csv');

        if (!File::exists($filePath) || !File::isReadable($filePath)) {
            $this->command->error('CSV file does not exist or is not readable.');
            return;
        }

        $data = array_map('str_getcsv', file($filePath));
        $header = array_shift($data);

        DB::beginTransaction();
        try {
            foreach ($data as $row) {
                $dataEntry = array_combine($header, $row);

                try {
                    DB::table('populate_data')->insert([
                        'end_year' => $dataEntry['end_year'] ? (int) $dataEntry['end_year'] : null,
                        'citylng' => $dataEntry['citylng'] ? (float) $dataEntry['citylng'] : null,
                        'citylat' => $dataEntry['citylat'] ? (float) $dataEntry['citylat'] : null,
                        'intensity' => $dataEntry['intensity'] ? (int) $dataEntry['intensity'] : null,
                        'sector' => $dataEntry['sector'],
                        'topic' => $dataEntry['topic'],
                        'insight' => $dataEntry['insight'],
                        'swot' => $dataEntry['swot'],
                        'url' => $dataEntry['url'],
                        'region' => $dataEntry['region'],
                        'start_year' => $dataEntry['start_year'] ? (int) $dataEntry['start_year'] : null,
                        'impact' => $dataEntry['impact'],
                        'added' => $dataEntry['added'] ? Carbon::createFromFormat('Y-m-d H:i:s', $dataEntry['added']) : null,
                        'published' => $dataEntry['published'] ? Carbon::createFromFormat('Y-m-d H:i:s', $dataEntry['published']) : null,
                        'city' => $dataEntry['city'],
                        'country' => $dataEntry['country'],
                        'relevance' => $dataEntry['relevance'] ? (int) $dataEntry['relevance'] : null,
                        'pestle' => $dataEntry['pestle'],
                        'source' => $dataEntry['source'],
                        'title' => $dataEntry['title'],
                        'likelihood' => $dataEntry['likelihood'] ? (int) $dataEntry['likelihood'] : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to insert row: ' . json_encode($dataEntry) . ' Error: ' . $e->getMessage());
                }
            }
            DB::commit();
            $this->command->info('Data imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Failed to import data: ' . $e->getMessage());
        }
    }
}
