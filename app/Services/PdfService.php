<?php

namespace App\Services;

use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;

class PdfService
{
    public function generate(string $view, array $data, string $filename): mixed
    {
        $browserPath = $this->resolveBrowserPath();

        $pdf = Pdf::view($view, $data)
            ->format('a4')
            ->download($filename)
            ->withBrowsershot(function (Browsershot $browsershot) use ($browserPath) {
                $browsershot
                    ->noSandbox()
                    ->dismissDialogs()
                    ->waitUntilNetworkIdle();

                if ($browserPath) {
                    $browsershot
                        ->setChromePath($browserPath)
                        ->setEnvironmentOptions(['PUPPETEER_EXECUTABLE_PATH' => $browserPath]);
                }
            });

        return $pdf;
    }

    private function resolveBrowserPath(): ?string
    {
        $configuredPath = config('pdf.browsershot.binary_path');
        if ($configuredPath) {
            return $configuredPath;
        }

        if (PHP_OS_FAMILY !== 'Windows') {
            return null;
        }

        $localAppData = getenv('LOCALAPPDATA') ?: '';
        $programFiles = getenv('PROGRAMFILES') ?: '';
        $programFilesX86 = getenv('PROGRAMFILES(X86)') ?: '';

        $candidates = [
            $programFiles . '\\Microsoft\\Edge\\Application\\msedge.exe',
            $programFilesX86 . '\\Microsoft\\Edge\\Application\\msedge.exe',
            $localAppData . '\\Microsoft\\Edge\\Application\\msedge.exe',
            $localAppData . '\\Programs\\Opera\\opera.exe',
            $programFiles . '\\Opera\\launcher.exe',
            $programFilesX86 . '\\Opera\\launcher.exe',
        ];

        foreach ($candidates as $path) {
            if ($path && file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
