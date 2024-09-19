<?php

namespace Fadaa\ReadyTests;

trait ReadyTestsHelper
{

    // get Table
    public function getReadyTestTable(): string
    {
        return config('ready-tests.tables.ready_tests');
    }

    // get ready test Model
    public function getReadyTestModel(): string
    {
        return config('ready-tests.models.ready_test');
    }

    // get question Model
    public function getReadyTestQuestionModel(): string
    {
        return config('ready-tests.models.ready_test_question');
    }






    // get ready test
    // by id
    public function getReadyTestById(int $id)
    {
        return app($this->getReadyTestModel())->byId($id)->first();
    }

    // by alias
    public function getReadyTestByAlias($alias, $locale = null)
    {
        return app($this->getReadyTestModel())->byAlias($alias, $this->getlocale($locale))->first();
    }





    // get question
    // by id
    public function getReadyTestQuestionById(int $id)
    {
        return app($this->getReadyTestQuestionModel())->byId($id)->first();
    }

    // by alias
    public function getReadyTestQuestionByAlias($alias, $locale = null)
    {
        return app($this->getReadyTestQuestionModel())->byAlias($alias, $this->getlocale($locale))->first();
    }




    private function getlocale($locale = null)
    {
        return $locale ? $locale : app()->getlocale();
    }



}
