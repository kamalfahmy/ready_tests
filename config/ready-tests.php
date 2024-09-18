<?php

return [
    'tables' => [
        'ready_tests' => 'ready_tests',
        'ready_test_questions' => 'ready_test_questions',
        'ready_test_question_answers' => 'ready_test_question_answers',
    ],

    'models' => [
        'ready_test' => Fadaa\ReadyTests\Models\ReadyTest::class,
        'ready_test_question' => Fadaa\ReadyTests\Models\ReadyTestQuestion::class,
        'ready_test_question_answer' => Fadaa\ReadyTests\Models\ReadyTestQuestionAnswer::class,
    ],

    'test_types' => [
        'final', 'mid_term', 'practical', // نشاط - منتصف ترم - اخر الترم
    ],

    'evaluation_methods' => [
        'auto', 'manual' // تصحيح تلقاءى - تصحيح من المعلم
    ],

    'question_types' => [
        'drop_list', 'essay'
    ],

    'statuses' => [
        'active', 'in_active'
    ],


    'test_defaults' => [
        'duration_minutes' => 15, // minutes , null = unlimited
        'max_attempts' => 2, // 2 attempts , null = unlimited
        'questions_count' => 10, // 10 questions , null = all questions
        'minutes_between_attempts' => 15, // minutes , null = no sepecfic minutes between attempts, user can test many attempts without a period of time between them
        'get_questions' => 'default', // random
        'percentage' => 100, // 100%
        'evaluation_method' => 'auto', // or manual

        'get_answers' => 'default', // random
    ],

];
