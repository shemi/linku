<?php

namespace Linku\Http\Controllers\Api;

use Response;
use Linku\Http\Controllers\Controller;
use Linku\Http\Requests\Request;
use Validator;

class ApiController extends Controller
{

    public function __construct()
    {

    }

    protected $status_code = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param int $status_code
     * @return $this
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;

        return $this;
    }


    public function respondNotFound($message = 'Not found.')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }



    public function respondNotAuthorized($message = 'not Authorized.')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }



    public function respondInternalError($message = 'Internal error.')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }



    public function respondWithError($message)
    {

        if( is_string($message)){
            $message = [
                'global' => [$message]
            ];
        }

        return Response::json([
            'error' => [
                'messages' => $message,
                'code'    => $this->getStatusCode()
            ]
        ], $this->getStatusCode());
    }



    public function respond($data, $headers = [])
    {
        return Response::json([
            'data' => $data,
            'code' => $this->getStatusCode()
        ], $this->getStatusCode(), $headers);
    }

    /**
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @return \Illuminate\Support\Facades\Validator
     */
    public function validator(Request $request, array $rules, array $messages = [])
    {
        return Validator::make($request->all(), $rules, $messages);
    }

}