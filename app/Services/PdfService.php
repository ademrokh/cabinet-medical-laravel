<?php

namespace App\Services;

use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;

class PdfService
{
    public function generate(string $view, array $data, string $filename): mixed
    {
        $chromePath = 'C:/Program Files/Google/Chrome/Application/chrome.exe';

        $pdf = Pdf::view($view, $data)
            ->format('a4')
            ->name($filename)
            ->withBrowsershot(function (Browsershot $browsershot) use ($chromePath) {
                $browsershot
                    ->setChromePath($chromePath)
                    ->noSandbox()
                    ->dismissDialogs()
                    ->setEnvironmentOptions(['PUPPETEER_EXECUTABLE_PATH' => $chromePath])
                    ->waitUntilNetworkIdle();
            });

        return $pdf;
    }
}
