<?php
namespace App\Controllers;

use App\Models\Question;

class QuestionController
{
    public function store()
    {
        $request = $_POST;
        $res = (new Question)->store($request);
        return $res;
    }

    public function answerQuestion()
    {
        $request = $_POST;
        $res = (new Question)->answerQuestion($request);
        return $res;
    }
}