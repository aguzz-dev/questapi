<?php 

namespace App\Helpers;

class JsonResponse
{
    public static function send($status, $message, $status_code = 200, $data = null)
    {
        header('Content-Type: application/json');
        if (!$status){
            http_response_code($status_code);
            echo json_encode([
                'status' => 'error',
                'message' => $message
            ]);
            exit;
        }
        if (is_null($data)){
            http_response_code($status_code);
            echo json_encode([
                'status' => 'success',
                'message' => $message
            ]);
            exit;
        }
        http_response_code($status_code);
        echo json_encode([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    public static function exception($e)
    {
        header('Content-Type: application/json');
        http_response_code($e->getCode());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        exit;
    }
}