<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait Response
{

    /**
     * Create a new JSON response instance.
     *
     * @param  string $message
     * @param  mixed  $result
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSuccessResponse($message, mixed $result = [], $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result
        ];

        return response()->json($response, $code);
    }

    /**
     * Create a new JSON response instance.
     *
     * @param  string $message
     * @param  array  $result
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendErrorResponse($message, $error = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($error)) {
            $response['data'] = $error;
        }

        return response()->json($response, $code);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendErrorResponse(message: "Validation Error", error: $validator->errors(), code: 422));
    }
}