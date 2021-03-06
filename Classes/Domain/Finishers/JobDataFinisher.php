<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterForm\Domain\Finishers;

/**
 * This file is part of the "jobrouter_form" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;
use TYPO3\CMS\Form\Domain\Finishers\Exception\FinisherException;

class JobDataFinisher extends AbstractFinisher
{
    private const TRANSFER_ACTION = 1;
    private const TRANSFER_TABLE = 'tx_jobrouterform_domain_model_transfer';

    protected function executeInternal()
    {
        $this->writeFormDataToDatabase();
    }

    protected function writeFormDataToDatabase(): void
    {
        $formData = $this->prepareData();

        if (empty($formData)) {
            return;
        }

        $tableUid = $this->getTableUid();

        $row = [
            'pid' => 0,
            'crdate' => time(),
            'form_identifier' => $this->getFormIdentifier(),
            'action' => static::TRANSFER_ACTION,
            'relation_uid' => $tableUid,
            'data' => \json_encode($formData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        ];

        $databaseConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable(static::TRANSFER_TABLE);

        $databaseConnection->insert(static::TRANSFER_TABLE, $row);
    }

    protected function prepareData(): array
    {
        $formValues = $this->getFormValues();

        $databaseData = [];
        foreach ($this->getColumns() as $columnName => $columnConfiguration) {
            $staticValue = $columnConfiguration['staticValue'] ?? false;
            if ($staticValue) {
                $databaseData[$columnName] = $staticValue;
            }

            $elementIdentifier = $columnConfiguration['mapOnFormField'] ?? false;
            if ($elementIdentifier) {
                $databaseData[$columnName] = $formValues[$elementIdentifier] ?? '';
            }
        }

        return $databaseData;
    }

    protected function getTableUid(): int
    {
        $tableUid = $this->parseOption('tableUid');

        if (!\is_numeric($tableUid)) {
            throw new FinisherException(
                \sprintf(
                    'tableUid must be a positive integer, "%s" given!',
                    $tableUid
                ),
                1573824631
            );
        }

        $tableUid = (int)$tableUid;

        if ($tableUid <= 0) {
            throw new FinisherException(
                \sprintf(
                    'tableUid must be a positive integer, "%d" given!',
                    $tableUid
                ),
                1573824632
            );
        }

        return $tableUid;
    }

    protected function getColumns(): array
    {
        return $this->parseOption('columns');
    }

    protected function getFormValues(): array
    {
        return $this->finisherContext->getFormValues();
    }

    protected function getElementByIdentifier(string $elementIdentifier)
    {
        return $this
            ->finisherContext
            ->getFormRuntime()
            ->getFormDefinition()
            ->getElementByIdentifier($elementIdentifier);
    }

    protected function getFormIdentifier(): string
    {
        return $this
            ->finisherContext
            ->getFormRuntime()
            ->getFormDefinition()
            ->getIdentifier();
    }
}
