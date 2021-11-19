<?php

use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
{
    $response = new Response(['error' => $validator->errors()->first()], 422);
    throw new ValidationException($validator, $response);
}


 function abortAction()
{
    return response()->json([
        'status'  => 403,
        'message' => 'You do not have the required authorization.'
    ]);
}


 function abortActionz(Exception $exception)
{
    if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
        return response()->json([
            'responseMessage' => 'You do not have the required authorization.',
            'responseStatus'  => 403,
        ]);
    }
    };


    function notFound()
    {
        return response()->json([
            'status'  => 403,
            'message' => 'No Data Found.'
        ]);
    }

   function quickRandom($length = 10)
{
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
}







