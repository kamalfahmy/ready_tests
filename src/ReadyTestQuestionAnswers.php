<?php

namespace Fadaa\ReadyTests;

trait ReadyTestQuestionAnswers
{


  // create new answer
  public function createAnswer(array $data)
  {

        // $validator = $this->validateAttribute($attribute);
        // if ($validator->fails()) {
        //   return $validator->errors();
        //   // throw new ValidationException($validator); // ???????
        // }


        // $this = ReadyTest Model
        return $this->answers()->create([
            'title' => $data['title'],
            'description' => isset($data['description']) ? $data['description'] : null,
            'image' => isset($data['image']) ? $data['image'] : null,
            'is_correct' => isset($data['is_correct']) ? $data['is_correct'] : 0,
            'sort' => $data['sort'],
            'status' => isset($data['status']) ? $data['status'] : config('ready-tests.statuses')[0],
            'ip' => isset($data['ip']) ? $data['ip'] : request()->ip(),
            'createdbyable_id' => $data['user']->id,
            'createdbyable_type' => get_class($data['user']),
        ]);

  }




}
