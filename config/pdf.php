<?php

return [
    'driver' => env('PDF_DRIVER', 'browsershot'),

    'browsershot' => [
        /**
         * The executable to use when running Browsershot commands.
         * By default, Browsershot will try to locate Chrome/Chromium automatically.
         * You can specify a custom path here.
         */
        'binary_path' => env('BROWSERSHOT_BIN', null),

        /**
         * Arguments to pass to Browsershot
         */
        'args' => [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
        ],

        /**
         * Timeout for rendering PDFs (in milliseconds)
         */
        'timeout' => 60000,

        /**
         * Screen width and height
         */
        'width' => 1280,
        'height' => 720,
    ],
];
