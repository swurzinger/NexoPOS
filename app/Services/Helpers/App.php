<?php

namespace App\Services\Helpers;

use App\Classes\Hook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait App
{
    /**
     * Is installed
     *
     * @return bool
     */
    public static function installed( $force = false )
    {
        if ( $force ) {
            $state = self::checkDatabaseExistence();
            Cache::set( 'ns-core-installed', $state );

            return $state;
        }

        /**
         * This cache is requested once per request
         * and cleared by the end of the request
         *
         * @see App\Http\Middleware\ClearRequestCacheMiddleware
         */
        return Cache::remember( 'ns-core-installed', 3600, function() {
            return self::checkDatabaseExistence();
        });
    }

    private static function checkDatabaseExistence()
    {
        try {
            if ( DB::connection()->getPdo() ) {
                return Schema::hasTable( 'nexopos_options' );
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function pageTitle( $string )
    {
        return sprintf(
            Hook::filter( 'ns-page-title', __( '%s &mdash; NexoPOS 4' ) ),
            $string );
    }

    /**
     * Checks if the "back" query parameter
     * has a valid URL otherwise uses the
     * previous URL stored on the session.
     */
    public static function getValidPreviousUrl( Request $request ): string
    {
        if ( $request->has('back') ) {
            $backUrl    = $request->query('back');
            $parsedUrl  = parse_url($backUrl);
            $host       = $parsedUrl['host'];

            if (filter_var($backUrl, FILTER_VALIDATE_URL) && $host === parse_url(env('APP_URL'), PHP_URL_HOST)) {
                return urldecode( $backUrl );
            } 
        }
        
        return url()->previous();
    }
}
