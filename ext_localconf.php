<?php
defined('TYPO3_MODE') or die();

(function ($extensionKey = 'jobrouter_form') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('
        module.tx_form {
            settings {
                yamlConfigurations {
                    1572978126 = EXT:jobrouter_form/Configuration/Yaml/BaseSetup.yaml
                    1572978127 = EXT:jobrouter_form/Configuration/Yaml/JobData/FormEditorSetup.yaml
                }
            }
        }
    
        plugin.tx_form {
            settings {
                yamlConfigurations {
                    1572978126 = EXT:jobrouter_form/Configuration/Yaml/BaseSetup.yaml
                }
            }
        }
    ');

    if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) < 10000000) {
        // TYPO3.CMS.Form.prototypes.standard.formEditor.translationFiles in FormEditorSetup.xaml
        // will be used in TYPO3 v10
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:form/Resources/Private/Language/Database.xlf'][1572978126]
            = 'EXT:' . $extensionKey . '/Resources/Private/Language/Database.xlf';
//        $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:form/Resources/Private/Language/Database.xlf'][1572978126]
//            = 'EXT:' . $extensionKey . '/Resources/Private/Language/de.Database.xlf';
    }
})();
