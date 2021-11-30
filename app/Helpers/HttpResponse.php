<?php

namespace App\Helpers;


class HttpResponse {

    public function sucess($sales)
    {
        return response()->json([
            'sucess' => true,
            'sales' => $sales
        ], 200);
    }


    public function serverError($error)
    {
        return response()->json([
            'sucess' => false,
            'error' => $error
        ], 500);
    }
}