<?php

namespace Fadaa\ReadyTests\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Fadaa\ReadyTests\Exceptions\InvalidDateFormatException;
use Fadaa\ReadyTests\Exceptions\InvalidArrayException;
use Fadaa\ReadyTests\ReadyTestQuestions; // trait (functions of questions like createQuestion())


class ReadyTest extends Model
{
    use ReadyTestQuestions;

    public function getTable()
    {
        return config('ready-tests.tables.ready_tests', 'ready_tests');
    }

    protected $fillable = [
        'title', 'alias', 'description','image',
        'readytestable_id', 'readytestable_type', 'createdbyable_id', 'createdbyable_type', 'type', 'duration_minutes',
        'max_attempts', 'questions_count_per_attempt', 'minutes_between_attempts', 'how_to_get_questions',
        'full_marks', 'pass_marks', 'percentage', 'evaluation_method',
        'available_from', 'available_to',
        'sort', 'properties', 'status', 'ip',
    ];

    protected $casts = [
      'title' => 'array',
      'alias' => 'array',
      'description' => 'array',
      'properties' => 'array',
      'available_from' => 'datetime',
      'available_to' => 'datetime',
    ];



    // the test is for term, diploma, course, ....
    public function readytestable(): MorphTo
    {
        return $this->morphTo();
    }

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

    public function questions()
    {
        return $this->hasMany($this->getReadyTestQuestionModel());
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

    public function scopeByType($query, $testType)
    {
      if (! $testType){
        return $query;
      }
      return $query->where('type', $testType);
    }

    public function scopeByTypes($query, $testTypes = [])
    {
      if (! is_array($testTypes)){
        throw new InvalidArrayException('Test Types must be an array');
      }

      if (empty($testTypes)){
        return $query;
      }

      return $query->whereIn('type', $testTypes);
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

    public function scopeByStatus($query, $status)
    {
      if (! $status){
        return $query;
      }
      return $query->where('status', $status);
    }

    public function scopeAvailableFrom($query, null|string|\DateTime $from)
    {
        if ($from === null) {
            return $query;
        }

        if (!$from instanceof \DateTime) {
            try {
                $from = \DateTime::createFromFormat('Y-m-d', $from);
            } catch (\Exception $e) {
                throw new InvalidDateFormatException();
            }
        }

        return $query->where('available_from', '>=', $from->format('Y-m-d H:i:s'));
    }

    public function scopeAvailableTo($query, null|string|\DateTime $to)
    {
        if ($to === null) {
            return $query;
        }

        if (!$to instanceof \DateTime) {
            try {
                $to = \DateTime::createFromFormat('Y-m-d', $to);
            } catch (\Exception $e) {
                throw new InvalidDateFormatException();
            }
        }

        return $query->where('available_to', '<=', $to->format('Y-m-d H:i:s'));
    }

    public function scopeByEvaluationMethod($query, $evaluationMethod)
    {
      if (! $evaluationMethod){
        return $query;
      }
      return $query->where('evaluation_method', $evaluationMethod);
    }

    public function scopeByEvaluationMethods($query, $evaluationMethods = [])
    {
      if (! is_array($evaluationMethods)){
        throw new InvalidArrayException('Evaluation methods must be an array');
      }

      if (empty($evaluationMethods)){
        return $query;
      }

      return $query->whereIn('evaluation_method', $evaluationMethods);
    }

    public function scopeSortedAscBy($query, $field)
    {
      return $query->orderBy($field ?? 'id', 'ASC');
    }

    public function scopeSortedDescBy($query, $field)
    {
      return $query->orderBy($field ?? 'id', 'DESC');
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








    public function correct(array $questionsWithAnswers)
    {
        // get questions(only selected questions that appeared to the user) with corrected answers
        $questions = $this->questions()->select('id', 'ready_test_id')
            ->with(['answers' => function($query) {
                $query->isCorrect()->select('id', 'question_id', 'is_correct');
            }])->find(array_keys($questionsWithAnswers));

        foreach ($questions as $question) {
            $question->correct($questionsWithAnswers[$question->id]); // user answers of this question
        }

    }








    // global functions
    private function getlocale($locale = null)
    {
      return $locale ? $locale : app()->getlocale();
    }

    // get question Model
    public function getReadyTestQuestionModel(): string
    {
        return config('ready-tests.models.ready_test_question');
    }


}
