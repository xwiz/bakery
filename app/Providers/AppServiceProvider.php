<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //register observers
        foreach ($this->getAppFiles('Observers') as $observer) {
            ("App\\Models\\$observer")::observe("App\\Observers\\{$observer}Observer");
        }
    }

    /**
     * Scan a directory
     * and return all file names.
     *
     * @param $path
     * @param null $delimiter
     * @return array
     */
    private function getAppFiles($path, $delimiter = null)
    {

        $filesPath = app_path($path);
        $delimiter = $delimiter ?? Str::singular($path);

        if(! is_dir($filesPath)){
            return [];
        }

        $files = scandir($filesPath);

        //strip off delimiter from file name.

        $files = array_map(function($file) use($delimiter){
            return explode($delimiter,$file)[0];
        }, $files);

        $files = array_filter($files, function($file){
            if(in_array($file, ['.','..']) || strpos($file,'.php') || $file == null){
                return false;
            }

            return true;
        });

        return array_values($files);
    }

}
