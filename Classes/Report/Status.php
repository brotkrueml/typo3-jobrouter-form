<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterForm\Report;

/*
 * This file is part of the "jobrouter_form" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\JobRouterForm\Domain\Repository\TransferRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Reports\ReportInterface;

class Status implements ReportInterface
{
    /** @var TransferRepository */
    private $transferRepository;

    public function __construct()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->transferRepository = $objectManager->get(TransferRepository::class);
    }

    public function getReport(): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName(
            'EXT:jobrouter_form/Resources/Private/Templates/StatusReport.html'
        ));

        $firstTransfer = $this->transferRepository->findFirst();
        $numberOfAllTransfers = $this->transferRepository->countAll();
        $inQueueTransfers = $this->transferRepository->findInQueueTransfers();
        $numberOfInQueueTransfers = \count($inQueueTransfers);
        $erroneousTransfers = $this->transferRepository->findErroneousTransfers();
        $numberOfErroneousTransfers = \count($erroneousTransfers);

        $settings = [
            'dateFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
            'timeFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
        ];

        $view->assignMultiple([
            'firstCreationDate' => $firstTransfer ? $firstTransfer->getCrdate() : 0,
            'numbers' => [
                'all' =>$numberOfAllTransfers,
                'successful' =>  $numberOfAllTransfers - $numberOfInQueueTransfers - $numberOfErroneousTransfers,
                'inQueue' => $numberOfInQueueTransfers,
                'erroneous' => $numberOfErroneousTransfers,
            ],
            'inQueueTransfers' => $inQueueTransfers,
            'erroneousTransfers' => $erroneousTransfers,
            'settings' => $settings,
        ]);

        return $view->render();
    }
}
