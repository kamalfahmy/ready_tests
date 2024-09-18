<?php

namespace Fadaa\ReadyTests\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Fadaa\ReadyTests\Exceptions\InvalidArrayException;
use Fadaa\ReadyTests\ReadyTestQuestionAnswers; // trait (functions of answers like createAnswer())

class ReadyTestQuestion extends Model
{
    use ReadyTestQuestionAnswers;

    public function getTable()
    {
        return config('ready-tests.tables.ready_test_questions', 'ready_test_questions');
    }

    protected $fillable = [
        'ready_test_id', 'type', 'title', 'alias', 'description', 'image',
        'createdbyable_id', 'createdbyable_type', 'duration_minutes',
        'marks', 'required', 'sort', 'properties',
        'status', 'ip'
    ];

    protected $casts = [
      'title' => 'array',
      'alias' => 'array',
      'description' => 'array',
      'properties' => 'array',
      'available_from' => 'datetime',
      'available_to' => 'datetime',
    ];


    // realations
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

    public function answers()
    {
        return $this->hasMany($this->getReadyTestQuestionAnswerModel());
    }













    // scopes
    public function scopeById($query, $id)
    {
      if (! $id){
        return $query;
      }
      return $query->where('id', $id);
    }

    public function scopeByAlias($query, $alias, $locale = null)
    {
      if (! $alias){
        return $query;
      }

      $locale = $this->getlocale($locale);
      return $query->whereJsonContains('alias->'.$locale, $alias);
    }

    public function scopeByUser($query, $user)
    {
      if (! $user){
        return $query;
      }
      return $query->where('createdbyable_id', $user->id)->where('createdbyable_type', get_class($user));
    }

    public function scopeByType($query, $questionType)
    {
      if (! $questionType){
        return $query;
      }
      return $query->where('type', $questionType);
    }

    public function scopeByTypes($query, $questionTypes = [])
    {
      if (! is_array($questionTypes)){
        throw new InvalidArrayException('Test Types must be an array');
      }

      if (empty($questionTypes)){
        return $query;
      }

      return $query->whereIn('type', $questionTypes);
    }

    public function scopeByDurationMinutes($query, $durationMinutes)
    {
      if (! $durationMinutes){
        return $query;
      }
      return $query->where('duration_minutes', $durationMinutes);
    }

    public function scopeByDurationMinutesGt($query, $durationMinutes)
    {
      if (! $durationMinutes){
        return $query;
      }
      return $query->where('duration_minutes', '>=',$durationMinutes);
    }

    public function scopeByDurationMinutesLt($query, $durationMinutes)
    {
      if (! $durationMinutes){
        return $query;
      }
      return $query->where('duration_minutes', '<=',$durationMinutes);
    }

    public function scopeGetRequiredQuestionsOnly($query, $required)
    {
      if (! $required){
        return $query;
      }
      return $query->where('required', 1);
    }

    public function scopeByStatus($query, $status)
    {
      if (! $status){
        return $query;
      }
      return $query->where('status', $status);
    }

    public function scopeSortQuestionsAscBy($query, $field)
    {
      if (! $field){
        return $query;
      }
      return $query->orderBy($field, 'ASC');
    }

    public function scopeSortQuestionsDescBy($query, $field)
    {
      if (! $field){
        return $query;
      }
      return $query->orderBy($field, 'DESC');
    }

    public function scopeLimitQuestions($query, $limit)
    {
      return $query->limit($limit);
    }

    public function scopeGetQuestions($query, $howToGetQuestions)
    {
      if ($howToGetQuestions == 'random') {
        return $query->inRandomOrder();
      }

      return $query;
    }








    // get fields
    public function getTranslatedTitle($locale = null)
    {
        $locale = $this->getlocale($locale);
        return isset($this->title[$locale]) ? $this->title[$locale] : '';
    }

    public function getTranslatedAlias($locale = null)
    {
        $locale = $this->getlocale($locale);
        return isset($this->alias[$locale]) ? $this->alias[$locale] : '';
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

    // question answer Model
    public function getReadyTestQuestionAnswerModel(): string
    {
        return config('ready-tests.models.ready_test_question_answer');
    }

}
