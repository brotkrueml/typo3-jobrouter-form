<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterForm\Domain\Repository;

/*
 * This file is part of the "jobrouter_form" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for transfers
 */
class TransferRepository extends Repository
{
    protected $defaultOrderings = [
        'crdate' => QueryInterface::ORDER_ASCENDING,
    ];

    public function findErroneousTransfers()
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd([
                $query->logicalNot(
                    $query->equals('transferMessage', '')
                ),
                $query->equals('transferSuccess', false),
            ])
        );

        return $query->execute();
    }

    public function findInQueueTransfers()
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd([
                $query->equals('transferDate', 0),
                $query->equals('transferSuccess', false),
            ])
        );

        return $query->execute();
    }

    public function findFirst()
    {
        $query = $this->createQuery();

        $result = $query->setLimit(1)->execute();

        if ($result instanceof QueryResultInterface) {
            return $result->getFirst();
        }
        if (\is_array($result)) {
            return $result[0] ?? null;
        }

        return null;
    }
}
