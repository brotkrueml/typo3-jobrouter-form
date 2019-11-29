<?php
defined('TYPO3_MODE') || die('Access denied.');

(function ($extensionKey = 'jobrouter_form') {
    $llPrefix = 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/Reports.xlf:';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_jobrouterform']['report'] = [
        'title'       => $llPrefix . 'title',
        'description' => $llPrefix . 'description',
        'icon'        => 'EXT:' . $extensionKey . '/Resources/Public/Icons/Extension.svg',
        'report'      => \Brotkrueml\JobRouterForm\Report\Status::class,
    ];
})();
