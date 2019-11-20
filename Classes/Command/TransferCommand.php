<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterForm\Command;

/*
 * This file is part of the "jobrouter_form" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\JobRouterForm\Transfer\JobDataTransfer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class TransferCommand extends Command
{
    private const TRANSFER_TABLE = 'tx_jobrouterform_domain_model_transfer';

    /**
     * @var Connection
     */
    private $databaseConnection;

    /**
     * @var int
     */
    private $NumberOfTotalEntriesProcessed = 0;

    /**
     * @var int
     */
    private $numberOfErroneousTransfers = 0;

    protected function configure(): void
    {
        $this
            ->setDescription('Transfer form data to JobRouter installations')
            ->setHelp('This command pushes the collected form data from finishers to JobRouter installations.');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $outputStyle = new SymfonyStyle($input, $output);

        $this->initialiseDatabaseConnection();
        $this->iterateOverNotTransferredEntries();

        if ($this->numberOfErroneousTransfers) {
            $outputStyle->error(
                \sprintf(
                    '%d number of entries were processed, but %d errors occurred!',
                    $this->NumberOfTotalEntriesProcessed,
                    $this->numberOfErroneousTransfers
                )
            );

            return 1;
        }

        if ($this->NumberOfTotalEntriesProcessed) {
            $outputStyle->success(
                \sprintf(
                    '%d entries were successfully transferred!',
                    $this->NumberOfTotalEntriesProcessed
                )
            );
        } else {
            $outputStyle->success('No transferable entries are available!');
        }

        return 0;
    }

    private function initialiseDatabaseConnection(): void
    {
        $this->databaseConnection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable(static::TRANSFER_TABLE);
    }

    private function iterateOverNotTransferredEntries(): void
    {
        $entries = $this->databaseConnection
            ->select(
                ['uid', 'action', 'relation_uid', 'data'],
                static::TRANSFER_TABLE,
                ['transfer_success' => 0]
            )
            ->fetchAll();

        $jobDataTransfer = GeneralUtility::makeInstance(JobDataTransfer::class);

        foreach ($entries as $entry) {
            if ($entry['action'] !== 1) {
                continue;
            }

            $this->NumberOfTotalEntriesProcessed++;

            try {
                $jobDataTransfer->transfer($entry['relation_uid'], \json_decode($entry['data'], true));
                $this->setTransferResult($entry['uid'], true);
            } catch (\Throwable $e) {
                $this->setTransferResult($entry['uid'], false, $e->getMessage());
                $this->numberOfErroneousTransfers++;
            }
        }
    }

    private function setTransferResult(int $transferUid, bool $isSuccess, string $errorMessage = ''): void
    {
        $this->databaseConnection
            ->update(
                static::TRANSFER_TABLE,
                [
                    'transfer_success' => $isSuccess,
                    'transfer_tstamp' => time(),
                    'error_message' => $errorMessage,
                ],
                ['uid' => $transferUid],
                [
                    'transfer_success' => Connection::PARAM_BOOL,
                    'transfer_tstamp' => Connection::PARAM_INT,
                    'error_message' => Connection::PARAM_STR,
                    'uid' => Connection::PARAM_INT,
                ]
            );
    }
}
