<?php
namespace App\Controllers;

use App\Models\Question;

class QuestionController
{
    public function store()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        $res = (new Question)->store($request);
        return $res;
    }

    public function answerQuestion()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        $res = (new Question)->answerQuestion($request);
        return $res;
    }
}