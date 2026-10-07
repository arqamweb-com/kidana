<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Partner API Rate Limit
    |--------------------------------------------------------------------------
    |
    | The number of requests each API client may make per minute.
    |
    */

    'rate_limit_per_minute' => (int) env('PARTNER_API_RATE_LIMIT', 120),

];
