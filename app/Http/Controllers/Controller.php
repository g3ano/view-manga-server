<?php

namespace App\Http\Controllers;

use App\Services\HttpResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, HttpResponse;

    /**
     * Relationships related to the model associated with this controller
     */
    protected $relationships = [];

    public function includeRelationship(Request $request)
    {
        $query = $request->query('include');
        $result = [];

        if (!empty($query)) {
            foreach ($query as $relation) {
                $relation = ctype_lower($relation)
                    ? $relation
                    : preg_replace_callback('/([A-Z])/', function ($groups) {
                        return '_' . strtolower($groups[1]);
                    }, $relation);

                if (in_array($relation, $this->relationships)) {
                    $result[] = $relation;
                }
            }
        }

        return $result;
    }
}
