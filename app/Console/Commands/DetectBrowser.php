<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DetectBrowser extends Command
{
    protected $signature = 'browser:detect';
    protected $description = 'Detect installed browsers for Browsershot';

    public function handle()
    {
        $this->info('🔍 Searching for installed browsers...');
        $this->newLine();

        $browsers = [
            'Google Chrome' => [
                'C:\Program Files\Google\Chrome\Application\chrome.exe',
                'C:\Program Files (x86)\Google\Chrome\Application\chrome.exe',
            ],
            'Microsoft Edge' => [
                'C:\Program Files\Microsoft\Edge\Application\msedge.exe',
                'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe',
            ],
            'Firefox' => [
                'C:\Program Files\Mozilla Firefox\firefox.exe',
                'C:\Program Files (x86)\Mozilla Firefox\firefox.exe',
            ],
            'Chromium' => [
                'C:\Program Files\Chromium\Application\chrome.exe',
                'C:\Program Files (x86)\Chromium\Application\chrome.exe',
            ],
        ];

        $found = false;

        foreach ($browsers as $name => $paths) {
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    $this->info("✅ Found: {$name}");
                    $this->line("   Path: {$path}");
                    $this->line("   Add to .env: BROWSERSHOT_BIN=\"{$path}\"");
                    $this->newLine();
                    $found = true;
                }
            }
        }

        if (!$found) {
            $this->error('❌ No browsers found!');
            $this->newLine();
            $this->info('Install one of the following:');
            $this->line('  • Google Chrome: https://www.google.com/chrome/');
            $this->line('  • Microsoft Edge: https://www.microsoft.com/edge');
            $this->line('  • Firefox: https://www.mozilla.org/firefox/');
        } else {
            $this->info('ℹ️  After adding BROWSERSHOT_BIN to .env, run: php artisan config:clear');
        }

        return $found ? 0 : 1;
    }
}
