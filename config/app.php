<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | Keep Acorn's detailed exception renderer out of production. Set
    | APP_DEBUG=true when you explicitly need Acorn's debug page.
    |
    */

    'debug' => filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
];
