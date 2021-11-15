<?php

use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
{
    $response = new Response(['error' => $validator->errors()->first()], 422);
    throw new ValidationException($validator, $response);
}


