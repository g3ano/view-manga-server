<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;

trait HttpResponse
{
    public function success(array $data, int $status = 200, $noWrap = false, $headers = [])
    {
        return response()->json(
            $noWrap ? $data : ['data' => $data],
            $status,
            $headers
        );
    }

    /**
     * Helper response for failed request
     * 
     * @return void
     * @throws HttpResponseException
     */
    public function failure(array $errors, int $status = 400, $headers = [])
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $errors
            ], $status, $headers)
        );
    }

    public function failedAsNotFound(string $resource = 'resource')
    {
        $this->failure([
            'message' => "No $resource is found"
        ], 404);
    }

    public function noContent()
    {
        return response()->noContent();
    }
}
