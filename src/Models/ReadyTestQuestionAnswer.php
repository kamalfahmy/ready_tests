<?php

namespace Fadaa\ReadyTests\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReadyTestQuestionAnswer extends Model
{

    public function getTable()
    {
        return config('ready-tests.tables.ready_test_question_answers', 'ready_test_question_answers');
    }

    protected $fillable = [
        'ready_test_question_id', 'title', 'description', 'image', 'is_correct', 'sort', 'status', 'ip',
        'createdbyable_id', 'createdbyable_type',
    ];

    protected $casts = [
      'title' => 'array',
      'description' => 'array',
    ];



    // the test creator, admin, teacher, ....
    public function createdable(): MorphTo
    {
        return $this->morphTo();
    }

    // wraper for morph createdable(), because user() is more readable than createdable()
    public function user()
    {
        return $this->createdable();
    }








    // scopes
    public function scopeById($query, $id)
    {
      if (! $id){
        return $query;
      }
      return $query->where('id', $id);
    }

    public function scopeByUser($query, $user)
    {
      if (! $user){
        return $query;
      }
      return $query->where('createdbyable_id', $user->id)->where('createdbyable_type', get_class($user));
    }

    public function scopeByStatus($query, $status)
    {
      if (! $status){
        return $query;
      }
      return $query->where('status', $status);
    }

    public function scopeSortAnswersAscBy($query, $field)
    {
      if (! $field){
        return $query;
      }
      return $query->orderBy($field, 'ASC');
    }

    public function scopeSortAnswersDescBy($query, $field)
    {
      if (! $field){
        return $query;
      }
      return $query->orderBy($field, 'DESC');
    }

    public function scopeGetAnswers($query, $howToGetAnswers)
    {
      if ($howToGetAnswers == 'random') {
        return $query->inRandomOrder();
      }

      return $query;
    }

    public function scopeGetCorrectedAnswers($query, $getCorrectedAnswers)
    {
      if ($getCorrectedAnswers) {
        return $query->where('is_correct', 1);
      }
      return $query;
    }

    public function scopeGetUncorrectedAnswers($query, $getUncorrectedAnswers)
    {
      if ($getUncorrectedAnswers) {
        return $query->where('is_correct', 0);
      }
      return $query;
    }








    // get fields
    public function getTranslatedTitle($locale = null)
    {
        $locale = $this->getlocale($locale);
        return isset($this->title[$locale]) ? $this->title[$locale] : '';
    }


    public function getTranslatedDescription($locale = null)
    {
        $locale = $this->getlocale($locale);
        return isset($this->title[$locale]) ? $this->description[$locale] : '';
    }

    public function getImage()
    {
      return ($this->image && Storage::exists($this->image)) ?  Storage::url($this->image) : null;
    }










    // global functions
    private function getlocale($locale = null)
    {
      return $locale ? $locale : app()->getlocale();
    }

}
