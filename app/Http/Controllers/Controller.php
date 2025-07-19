<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
     /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function sendResponse( $message, $data = [], $code = 200)

    {


    	$response = [

            'success' => true,
            'message' => $message,
            'data'    => $data,
            'errors'  => [],
            'code'    => $code

        ];



        return response()->json($response, 200);

    }



    /**

     * return error response.

     *

     * @return \Illuminate\Http\Response

     */

    public function sendError($error, $errorMessages = [], $code = 404)

    {

    	$response = [

            'success' => false,
            'message' => $error,
            'data'    => [],
            'errors'  => $errorMessages,
            'code'    => $code

        ];



        if(!empty($errorMessages)){

            $response['data'] = $errorMessages;

        }



        return response()->json($response, $code);

    }
}
