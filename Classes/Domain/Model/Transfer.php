<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterForm\Domain\Model;

/*
 * This file is part of the "jobrouter_form" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Transfer model
 */
class Transfer extends AbstractEntity
{
    /** @var int */
    protected $crdate = 0;

    /** @var string */
    protected $formIdentifier = '';

    /** @var int */
    protected $action = 0;

    /** @var int */
    protected $relationUid = 0;

    /** @var string */
    protected $data = '';

    /** @var bool */
    protected $transferSuccess = false;

    /** @var int */
    protected $transferDate = 0;

    /** @var int */
    protected $transferCode = 0;

    /** @var string */
    protected $transferMessage = '';

    public function getCrdate(): int
    {
        return $this->crdate;
    }

    public function setCrdate(int $crdate): void
    {
        $this->crdate = $crdate;
    }

    public function getFormIdentifier(): string
    {
        return $this->formIdentifier;
    }

    public function setFormIdentifier(string $formIdentifier): void
    {
        $this->formIdentifier = $formIdentifier;
    }

    public function getAction(): int
    {
        return $this->action;
    }

    public function setAction(int $action): void
    {
        $this->action = $action;
    }

    public function getRelationUid(): int
    {
        return $this->relationUid;
    }

    public function setRelationUid(int $relationUid): void
    {
        $this->relationUid = $relationUid;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }

    public function isTransferSuccess(): bool
    {
        return $this->transferSuccess;
    }

    public function setTransferSuccess(bool $transferSuccess): void
    {
        $this->transferSuccess = $transferSuccess;
    }

    public function getTransferDate(): int
    {
        return $this->transferDate;
    }

    public function setTransferDate(int $transferDate): void
    {
        $this->transferDate = $transferDate;
    }

    public function getTransferCode(): int
    {
        return $this->transferCode;
    }

    public function setTransferCode(int $transferCode): void
    {
        $this->transferCode = $transferCode;
    }

    public function getTransferMessage(): string
    {
        return $this->transferMessage;
    }

    public function setTransferMessage(string $transferMessage): void
    {
        $this->transferMessage = $transferMessage;
    }

    public function getContentElementUid(): int
    {
        $parts = \explode('-', $this->formIdentifier);

        return (int)\array_pop($parts);
    }
}
