<?php

namespace App\Exceptions;

use App\Services\Helper;
use Exception;

class ModuleVersionMismatchException extends Exception
{
    public function __construct( $message = null )
    {
        $this->message = $message ?: __('A database issue has occurred.' );
    }

    public function render( $request )
    {
        $message = $this->getMessage();
        $title = __( 'Module Version Mismatch' );
        $back   = Helper::getValidPreviousUrl( $request );

        return response()->view( 'pages.errors.module-exception', compact( 'message', 'title', 'back' ), 500 );
    }
}
