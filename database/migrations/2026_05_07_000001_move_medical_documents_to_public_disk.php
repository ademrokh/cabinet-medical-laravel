<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $local = Storage::disk('local');
        $public = Storage::disk('public');

        if (!$local->exists('medical-documents')) {
            return;
        }

        foreach ($local->files('medical-documents') as $path) {
            if ($public->exists($path)) {
                continue;
            }

            $stream = $local->readStream($path);

            if ($stream === false) {
                continue;
            }

            $public->put($path, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }

            $local->delete($path);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $public = Storage::disk('public');
        $local = Storage::disk('local');

        if (!$public->exists('medical-documents')) {
            return;
        }

        foreach ($public->files('medical-documents') as $path) {
            if ($local->exists($path)) {
                continue;
            }

            $stream = $public->readStream($path);

            if ($stream === false) {
                continue;
            }

            $local->put($path, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }

            $public->delete($path);
        }
    }
};
