<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateUserReport extends Command
{
    protected $signature = 'report:generate';
    protected $description = 'Generate weekly report of new users and send via email';

    public function handle()
    {
        try {
            $users = User::where('created_at', '>=', Carbon::now()->subDays(7))->get();

            if ($users->isEmpty()) {
                $this->info('No new users to report.');
                return;
            }

            $csvFileName = 'weekly_user_report.csv';
            $csvPath = storage_path('app/' . $csvFileName);

            $file = fopen($csvPath, 'w');
            if ($file === false) {
                throw new \Exception('Unable to open file for writing: ' . $csvPath);
            }

            fputcsv($file, ['ID', 'Name', 'Email', 'Created At']);

            foreach ($users as $user) {
                fputcsv($file, [$user->id, $user->name, $user->email, $user->created_at]);
            }

            fclose($file);

            // Send email with the CSV attachment
            Mail::raw('Please find attached the weekly report of new users.', function ($message) use ($csvFileName, $csvPath) {
                $message->to('info@walliftransport.com')
                    ->subject('Weekly User Report')
                    ->attach($csvPath);
            });

            $this->info('Report generated and emailed successfully.');
            Log::info('Weekly user report generated and emailed successfully.');

        } catch (\Exception $e) {
            Log::error('Error generating or sending the report: ' . $e->getMessage());
            $this->error('Failed to generate or send the report. Please check the logs for details.');
        }
    }
}
