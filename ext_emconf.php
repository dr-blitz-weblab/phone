<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Phone',
    'description' => 'Adds phone number functionality to TYPO3. Crate f.link.phone helper with the definition  ITU-T INTERNATIONAL,NATIONAL,E123',
    'category' => 'plugin',
    'author' => 'Krzysztof Napora',
    'author_company' => 'DR BLITZ WEBLAB',
    'author_email' => 'office@drblitz-weblab.com',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.19-12.9.99',
            'php' => '7.4.0-8.2.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
