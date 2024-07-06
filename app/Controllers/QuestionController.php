<?php
namespace App\Controllers;

use Exception;
use App\Models\Question;
use App\Helpers\JsonRequest;
use App\Helpers\JsonResponse;

class QuestionController
{
    public function getQuestionById()
    {
        $request = JsonRequest::get();
        $res = (new Question)->find($request->id);
        if(empty($res)){
            JsonResponse::send(false, 'Pregunta no encontrada', 404);
        }
        JsonResponse::send(true, 'Solicitud exitosa', 200, $res);
    }

    public function getQuestionsByPostId()
    {
        $postId = JsonRequest::get()->id;
        return (new Question)->getQuestionsByPostId($postId);
    }

    public function store()
    {
        $request = JsonRequest::get();
        try{
            $res = (new Question)->store($request);
            JsonResponse::send(true, 'Pregunta creada con éxito', 200, $res);
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }

    public function storeQuestionFromWeb()
    {
        $request = $_POST;
        try{
            $res = (new Question)->store((object)$request);
            JsonResponse::send(true, 'Pregunta creada con éxito', 200, $res);
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }

    public function answerQuestion()
    {
        $request = JsonRequest::get();
        try{
            $res = (new Question)->answerQuestion($request);
            JsonResponse::send(true, 'Se ha actualizado el estado de la pregunta a Respondida', 200, $res);
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }
}