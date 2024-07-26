<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function api_response_success($data = null, $message = null)
{
    $response = [
        'meta' => [
            'code' => 200,
            'status' => 'success',
            'message' => $message
        ],
        'data' => $data
    ];

    $CI =& get_instance();
    $CI->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($response));
}

function api_response_error($data = null, $message = null, $code = 400)
{
    $response = [
        'meta' => [
            'code' => $code,
            'status' => 'error',
            'message' => $message
        ],
        'data' => $data
    ];

    $CI =& get_instance();
    $CI->output
        ->set_content_type('application/json')
        ->set_status_header($code)
        ->set_output(json_encode($response));
}