<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function index()
    {
        // Get list of existing backups
        $backupPath = storage_path('app/backups');
        
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $backups = collect(scandir($backupPath))
            ->filter(fn($file) => str_ends_with($file, '.sql'))
            ->map(function($file) use ($backupPath) {
                $filePath = $backupPath . '/' . $file;
                return [
                    'name' => $file,
                    'size' => $this->formatBytes(filesize($filePath)),
                    'date' => date('Y-m-d H:i:s', filemtime($filePath)),
                    'path' => $filePath,
                ];
            })
            ->sortByDesc('date')
            ->values();

        return view('admin.backup.index', compact('backups'));
    }

    public function create()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $backupPath = storage_path('app/backups/' . $filename);

            // Get database credentials
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');

            // Create backup using mysqldump
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($database),
                escapeshellarg($backupPath)
            );

            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Backup failed');
            }

            activity()
                ->causedBy(auth()->user())
                ->log("Created database backup: {$filename}");

            return redirect()->back()->with('success', 'Database backup created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Backup file not found.');
        }

        activity()
            ->causedBy(auth()->user())
            ->log("Downloaded database backup: {$filename}");

        return response()->download($filePath);
    }

    public function delete($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Backup file not found.');
        }

        unlink($filePath);

        activity()
            ->causedBy(auth()->user())
            ->log("Deleted database backup: {$filename}");

        return redirect()->back()->with('success', 'Backup deleted successfully!');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
