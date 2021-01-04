<?php

namespace Label84\LogViewer\Console\Commands;

use Illuminate\Console\Command;

class InstallLogViewer extends Command
{
    protected $hidden = true;

    protected $signature = 'logviewer:install';

    protected $description = 'Install the LogViewer package.';

    public function handle(): void
    {
        $this->info('Installing log viewer package...');

        $this->call('vendor:publish', [
            '--provider' => "Label84\LogViewer\LogViewerServiceProvider",
            '--tag' => 'config'
        ]);

        $this->info('All done!');
    }
}
