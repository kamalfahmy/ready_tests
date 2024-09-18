<?php

namespace Fadaa\ReadyTests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ReadyTests
{

    // get Table
    public function getReadyTestTable(): string
    {
        return config('ready-tests.tables.ready_tests');
    }

    // get Model
    public function getReadyTestModel(): string
    {
        return config('ready-tests.models.ready_test');
    }





    // relation : this test belongs to (term, diploma, course, ....)
    public function readyTests(): MorphMany
    {
        return $this->morphMany($this->getReadyTestModel(), 'readytestable');
    }

    // relation : user who created the test (admin, teacher, ....)
    public function createdReadyTests(): MorphMany
    {
        return $this->morphMany($this->getReadyTestModel(), 'createdable');
    }





    // create new test
    public function createRadyTest(array $data)
    {

            // $validator = $this->validateAttribute($attribute);
            // if ($validator->fails()) {
            //   return $validator->errors();
            //   // throw new ValidationException($validator); // ???????
            // }

            // return $this->readyTests()->create([
            //   'title' => $data['title'],
            //   'description' => @$data['description'],
            //   'image' => @$data['image'],
            //
            //   'readytestable_id' => $this->getKey(),
            //   'readytestable_type' => get_class($this),
            //   'createdbyable_id' => $data['user']->id,
            //   'createdbyable_type' => get_class($data['user']),
            //
            //   'type' => $data['type'], // نشاط تيرم منتصف تيرم
            //   'duration_minutes' => isset($data['duration_minutes']) ? $data['duration_minutes'] : config('ready-tests.test_defaults.duration_minutes'),
            //   'max_attempts' => isset($data['max_attempts']) ? $data['max_attempts'] : config('ready-tests.test_defaults.max_attempts'),
            //   'questions_count_per_attempt' => isset($data['questions_count_per_attempt']) ? $data['questions_count_per_attempt'] : config('ready-tests.test_defaults.questions_count_per_attempt'),
            //   'minutes_between_attempts' => isset($data['minutes_between_attempts']) ? $data['minutes_between_attempts'] : config('ready-tests.test_defaults.minutes_between_attempts'),
            //   'how_to_get_questions' => isset($data['how_to_get_questions']) ? $data['how_to_get_questions'] : config('ready-tests.test_defaults.how_to_get_questions'),
            //   'full_marks' => $data['full_marks'],
            //   'pass_marks' => $data['pass_marks'],
            //   'percentage' => isset($data['percentage']) ? $data['percentage'] : config('ready-tests.test_defaults.percentage'),
            //   'evaluation_method' => isset($data['evaluation_method']) ? $data['evaluation_method'] : config('ready-tests.test_defaults.evaluation_method'),
            //   'available_from' => isset($data['available_from']) ? $data['available_from'] : null,
            //   'available_to' => isset($data['available_to']) ? $data['available_to'] : null,
            //   'sort' => isset($data['sort']) ? $data['sort'] : 0,
            //   'properties' => isset($data['properties']) ? $data['properties'] : null,
            //   'status' => isset($data['status']) ? $data['status'] : config('ready-tests.statuses')[0],
            //   'ip' => isset($data['ip']) ? $data['ip'] : request()->ip(),
            // ]);

            return $this->readyTests()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,

            'readytestable_id' => $this->getKey(),
            'readytestable_type' => get_class($this),
            'createdbyable_id' => $data['user']->id,
            'createdbyable_type' => get_class($data['user']),

            'type' => $data['type'], // نشاط تيرم منتصف تيرم
            'duration_minutes' => $data['duration_minutes'] ?? config('ready-tests.test_defaults.duration_minutes'),
            'max_attempts' => $data['max_attempts'] ?? config('ready-tests.test_defaults.max_attempts'),
            'questions_count_per_attempt' => $data['questions_count_per_attempt'] ?? config('ready-tests.test_defaults.questions_count_per_attempt'),
            'minutes_between_attempts' => $data['minutes_between_attempts'] ?? config('ready-tests.test_defaults.minutes_between_attempts'),
            'how_to_get_questions' => $data['how_to_get_questions'] ?? config('ready-tests.test_defaults.how_to_get_questions'),
            'full_marks' => $data['full_marks'],
            'pass_marks' => $data['pass_marks'],
            'percentage' => $data['percentage'] ?? config('ready-tests.test_defaults.percentage'),
            'evaluation_method' => $data['evaluation_method'] ?? config('ready-tests.test_defaults.evaluation_method'),
            'available_from' => $data['available_from'] ?? null,
            'available_to' => $data['available_to'] ?? null,
            'sort' => $data['sort'] ?? 0,
            'properties' => $data['properties'] ?? null,
            'status' => $data['status'] ?? config('ready-tests.statuses')[0],
            'ip' => $data['ip'] ?? request()->ip(),
            ]);

    }








    //   main 1, get ready tests only
    public function getReadyTests($filters = [])
    {

        $query = $this->readyTests()
            ->ById($filters['id'] ?? null)
            ->ByAlias($filters['alias'] ?? null)
            ->ByUser($filters['user'] ?? null)
            ->ByType($filters['type'] ?? null)
            ->ByTypes($filters['types'] ?? null)
            ->ByDurationMinutes($filters['duration_minutes'] ?? null)
            ->ByDurationMinutesGt($filters['duration_minutes_gt'] ?? null)
            ->ByDurationMinutesLt($filters['duration_minutes_lt'] ?? null)
            ->ByStatus($filters['status'] ?? null)
            ->AvailableFrom($filters['available_from'] ?? null)
            ->AvailableTo($filters['available_to'] ?? null)
            ->ByEvaluationMethod($filters['evaluation_method'] ?? null)
            ->ByEvaluationMethods($filters['evaluation_methods'] ?? null)
            ->SortedAscBy($filters['sort_asc'] ?? null)
            ->SortedDescBy($filters['sort_desc'] ?? null);

            // Determine the appropriate method to return results based on the use case
            if (isset($filters['paginate'])) {
                return $query->paginate($filters['paginate']);
            } elseif (isset($filters['limit'])) {
                return $query->limit($filters['limit'])->get();
            } elseif (isset($filters['first'])) {
                return $query->first();
            } else {
                return $query->get();
            }

    }

    // main 2, get ready tests with questions and answers
    public function getReadyTestsWithQuestions($filters = [])
    {


        $query = $this->readyTests()
            ->ById($filters['id'] ?? null)
            ->ByAlias($filters['alias'] ?? null)
            ->ByUser($filters['user'] ?? null)
            ->ByType($filters['type'] ?? null)
            ->ByTypes($filters['types'] ?? [])
            ->ByDurationMinutes($filters['duration_minutes'] ?? null)
            ->ByDurationMinutesGt($filters['duration_minutes_gt'] ?? null)
            ->ByDurationMinutesLt($filters['duration_minutes_lt'] ?? null)
            ->ByStatus($filters['status'] ?? null)
            ->AvailableFrom($filters['available_from'] ?? null)
            ->AvailableTo($filters['available_to'] ?? null)
            ->ByEvaluationMethod($filters['evaluation_method'] ?? null)
            ->ByEvaluationMethods($filters['evaluation_methods'] ?? [])
            ->SortedAscBy($filters['sort_asc_by'] ?? null)
            ->SortedDescBy($filters['sort_desc_by'] ?? null);



            $query->with(['questions' => function($query) use ($filters){
                // questions filters
                $query->getQuestions($filters['get_questions'] ?? config('ready-tests.test_defaults.get_questions'))
                    ->byStatus($filters['question_status'] ?? null)
                    ->limitQuestions($filters['questions_count'] ?? config('ready-tests.test_defaults.questions_count'))
                    ->getRequiredQuestionsOnly($filters['required_questions_only'] ?? null)
                    ->sortQuestionsAscBy($filters['sort_questions_asc_by'] ?? null)
                    ->sortQuestionsDescBy($filters['sort_questions_desc_by'] ?? null)


                    // answers filters
                    ->with(['answers' => function($query) use ($filters){
                      $query->getAnswers($filters['get_answers'] ?? config('ready-tests.test_defaults.get_answers'))
                      ->byStatus($filters['answer_status'] ?? null)
                      ->getCorrectedAnswers($filters['get_corrected_answers'] ?? null)
                      ->getUncorrectedAnswers($filters['get_uncorrected_answers'] ?? null)
                      ->sortAnswersAscBy($filters['sort_answers_asc_by'] ?? null)
                      ->sortAnswersDescBy($filters['sort_answers_desc_by'] ?? null);
                    }]);

            }]);


            return $query;

            // // paginate or limit or first ..
            // if (isset($filters['paginate'])) {
            //     return $query->paginate($filters['paginate']);
            // } elseif (isset($filters['limit'])) {
            //     return $query->limit($filters['limit'])->get();
            // } elseif (isset($filters['first'])) {
            //     return $query->first();
            // } else {
            //     return $query->get();
            // }

    }







    private function getlocale($locale = null)
    {
        return $locale ? $locale : app()->getlocale();
    }







}
