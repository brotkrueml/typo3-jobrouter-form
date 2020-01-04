<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'JobRouter Form',
    'description' => 'Form finishers for pushing data to JobRouter installations',
    'category' => 'plugin',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'alpha',
    'version' => '0.2.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
            'jobrouter_connector' => '0.5.0-0.0.0',
            'jobrouter_data' => '0.4.0-0.0.0',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\JobRouterData\\' => 'Classes']
    ],
];
