<?php
namespace App\Controllers;

use App\Helpers\JsonResponse;
use App\Models\Question;
use Exception;

class QuestionController
{
    public function store()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        try{
            $res = (new Question)->store($request);
            JsonResponse::send(true, 'Pregunta creada con éxito', 200, $res);
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }

    public function answerQuestion()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        try{
            $res = (new Question)->answerQuestion($request);
            JsonResponse::send(true, 'Se ha actualizado el estado de la pregunta a Respondida', 200, $res);
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }
}