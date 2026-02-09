<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\QueryFilter;
use App\Traits\ApiResponses;

class ApiController extends Controller
{
    use ApiResponses;

    /**
     * Determine if a given relationship is included in the request.
     *
     * Checks the `include` query parameter for a specific relationship name.
     */
    public function includes(string $relationship): bool
    {
        $param = request()->get('include');

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }
}
