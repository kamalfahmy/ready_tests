<?php

namespace Fadaa\ReadyTests;

trait ReadyTestQuestions
{


  // create new question
  public function createQuestion(array $data)
  {

        // $validator = $this->validateAttribute($attribute);
        // if ($validator->fails()) {
        //   return $validator->errors();
        //   // throw new ValidationException($validator); // ???????
        // }


        // $this = ReadyTest Model
        return $this->questions()->create([
            // 'ready_test_id' => $data['ready_test_id'],
            'type' => $data['type'],
            'title' => $data['title'],
            'alias' => isset($data['alias']) ? $data['alias'] : null,
            'description' => isset($data['description']) ? $data['description'] : null,
            'image' => @$data['image'],


            'createdbyable_id' => $data['user']->id,
            'createdbyable_type' => get_class($data['user']),

            'duration_minutes' => isset($data['duration_minutes']) ? $data['duration_minutes'] : config('ready-tests.test_defaults.duration_minutes'),
            'marks' => $data['marks'],
            // 'correct_answers' => $data['correct_answers'],
            'required' => isset($data['required']) ? $data['required'] : 0,

            'sort' => isset($data['sort']) ? $data['sort'] : 0,
            'properties' => isset($data['properties']) ? $data['properties'] : null,
            'status' => isset($data['status']) ? $data['status'] : config('ready-tests.statuses')[0],
            'ip' => isset($data['ip']) ? $data['ip'] : request()->ip(),


        ]);

  }




}
