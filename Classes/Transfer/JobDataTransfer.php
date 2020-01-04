<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterForm\Transfer;

/*
 * This file is part of the "jobrouter_form" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\JobRouterClient\Client\RestClient;
use Brotkrueml\JobRouterConnector\RestClient\RestClientFactory;
use Brotkrueml\JobRouterData\Domain\Model\Table;
use Brotkrueml\JobRouterData\Domain\Repository\TableRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

final class JobDataTransfer implements SingletonInterface
{
    /**
     * @var TableRepository
     */
    private $tableRepository;

    /**
     * @var Table[]
     */
    private $tables = [];

    /**
     * @var RestClient[]
     */
    private $restClients = [];

    public function __construct()
    {
        $this->tableRepository = GeneralUtility::makeInstance(ObjectManager::class)
            ->get(TableRepository::class);
    }

    public function transfer(int $tableUid, array $dataset): void
    {
        $table = $this->getTable($tableUid);
        $restClient = $this->getRestClient($table);

        $resourcePath = \sprintf('application/jobdata/tables/%s/datasets', $table->getTableGuid());
        $response = $restClient->request('POST', $resourcePath, ['json' => ['dataset' => $dataset]]);

        $statusCode = $response->getStatusCode();
        if ($statusCode === 201) {
            return;
        }

        $resourceUrl = $table->getConnection()->getBaseUrl() . $resourcePath;
        $content = $response->getBody()->getContents();
        throw new TransferException(
            \sprintf(
                'POST to resource "%s" returned status code "%d"%s',
                $resourceUrl,
                $statusCode,
                $content ? 'with content "' . $content . '"': ''
            ),
            1574274927
        );
    }

    private function getTable(int $tableUid): ?Table
    {
        if (!isset($this->tables[$tableUid])) {
            $table = $this->tableRepository->findByIdentifier($tableUid);

            if (empty($table)) {
                throw new TransferException(
                    \sprintf(
                        'Table with uid "%d" could not be found or is disabled!',
                        $tableUid
                    ),
                    1573844595
                );
            }

            if (empty($table->getConnection())) {
                throw new TransferException(
                    \sprintf(
                        'Connection for table with uid "%d" is empty or disabled!',
                        $tableUid
                    ),
                    1573844596
                );
            }

            $this->tables[$tableUid] = $table;
        }

        return $this->tables[$tableUid];
    }

    private function getRestClient(Table $table): ?RestClient
    {
        $connectionUid = $table->getConnection()->getUid();

        if (!isset($this->restClients[$connectionUid])) {
            try {
                $restClient = (new RestClientFactory)->create($table->getConnection());
            } catch (\Throwable $e) {
                throw new TransferException(
                    \sprintf(
                        'Rest client could not be initialised: %s',
                        $e->getMessage()
                    ),
                    1574278289,
                    $e
                );
            }

            $this->restClients[$connectionUid] = $restClient;
        }

        return $this->restClients[$connectionUid];
    }
}
