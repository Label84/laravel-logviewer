<?php

use Label84\LogViewer\Support\LogViewerLevel;

return [

    'route' => [
        /**
         * URL to visit the page.
         */
        'prefix' => '/admin/logviewer',

        /**
         * Apply Middleware to prevent unwanted access to the page.
         */
        'middleware' => [
            'web',
            // 'auth',
        ],
    ],

    'view' => [
        /**
         * Sets the name on the page.
         */
        'title' => 'LogViewer',

        /**
         * Limit the number of items per page.
         */
        'items_per_page' => 50,

        /**
         * Hide/show the channel name in the top-right corner.
         */
        'show_logger' => true,
    ],

    /**
     * Directory containing your log files.
     *
     * Note: you probably don't need to change this.
     */
    'log_files_directory' => storage_path('logs'),

    /*
     * Set the log channel of your application.
     *
     * single => the package will use laravel.log
     * daily => the package will use latest daily log file created with the format laravel-YYYY-MM-DD.log
     */
    'log_channel' => 'single',

    /*
     * Recently created log records will be highlighted in bold text.
     *
     * To disable set the value to: 0
     */
    'marked_as_new_in_minutes' => 60,

    /*
     * Minimum threshold level for records, everything below this threshold will be excluded.
     */
    'minimum_level' => LogViewerLevel::DEBUG,

    /*
     * Maximum allowed file size of the log file in MB. To prevent memory exhaustion errors.
     */
    'max_file_size' => 25,

    /*
     * TailwindCSS classes to be applied to the log level badges in the view.
     */
    'classes' => [
        'debug' => 'bg-gray-100 text-gray-800',
        'info' => 'bg-blue-100 text-blue-800',
        'notice' => 'bg-blue-100 text-blue-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'error' => 'bg-red-100 text-red-800',
        'critical' => 'bg-red-100 text-red-800',
        'alert' => 'bg-red-100 text-red-800',
        'emergency' => 'bg-red-100 text-red-800',
    ],
];
