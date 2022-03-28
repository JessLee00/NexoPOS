<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\Modules;
use App\Services\Setup;
use App\Services\Helper;
use App\Services\ModulesService;

class ModuleEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:event {namespace} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a module event';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modules    =   app()->make( ModulesService::class );

        /**
         * Check if module is defined
         */
        if ( $module = $modules->get( $this->argument( 'namespace' ) ) ) {
            /**
             * Define Variables
             */
            $eventsPath     =   $module[ 'namespace' ] . DIRECTORY_SEPARATOR . 'Events' . DIRECTORY_SEPARATOR;
            $name           =   ucwords( Str::camel( $this->argument( 'name' ) ) );
            $fileName       =   $eventsPath . $name;
            $namespace      =   $this->argument( 'namespace' );

            if ( ! Storage::disk( 'ns-modules' )->exists( 
                $fileName 
            ) ) {
                Storage::disk( 'ns-modules' )->put( $fileName . '.php', view( 'generate.modules.event', compact(
                    'modules', 'module', 'name', 'namespace'
                ) ) );
                return $this->info( 'The event has been created !' );
            }      
            return $this->error( 'The event already exists !' );
        }
        return $this->error( 'Unable to locate the module !' );
    }
}
