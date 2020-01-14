<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ApiResponseFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'api_response';
    }

}
