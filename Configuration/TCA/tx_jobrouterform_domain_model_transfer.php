<?php
return [
    'ctrl' => [
        'title' => 'tx_jobrouterform_domain_model_transfer',
        'label' => 'form_identifier',
        'adminOnly' => true,
        'rootLevel' => 1,
        'hideTable' => true,
        'default_sortby' => 'uid DESC',
    ],
    'columns' => [
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'input'
            ]
        ],
        'form_identifier' => [
            'label' => 'form_identifier',
            'config' => [
                'type' => 'input'
            ]
        ],
        'action' => [
            'label' => 'action',
            'config' => [
                'type' => 'input'
            ]
        ],
        'relation_uid' => [
            'label' => 'relation_uid',
            'config' => [
                'type' => 'input'
            ]
        ],
        'data' => [
            'label' => 'data',
            'config' => [
                'type' => 'input'
            ]
        ],
        'transfer_success' => [
            'label' => 'transfer_success',
            'config' => [
                'type' => 'input'
            ]
        ],
        'transfer_date' => [
            'label' => 'transfer_date',
            'config' => [
                'type' => 'input'
            ]
        ],
        'transfer_message' => [
            'label' => 'transfer_message',
            'config' => [
                'type' => 'input'
            ]
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => 'form_identifier, action, relation_uid, data, transfer_success, transfer_date, transfer_message'
        ]
    ]
];
