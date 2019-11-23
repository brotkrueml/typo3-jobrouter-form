<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterForm\Tests\Unit\Domain\Finishers;

use Brotkrueml\JobRouterForm\Domain\Finishers\JobDataFinisher;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Form\Domain\Finishers\Exception\FinisherException;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class JobDataFinisherTest extends UnitTestCase
{
    /**
     * @test
     */
    public function prepareDataProcessesMapOnFormFieldCorrectly(): void
    {
        /** @var $jobDataFinisher MockObject|AccessibleObjectInterface */
        $jobDataFinisher = $this->getAccessibleMock(
            JobDataFinisher::class,
            ['getFormValues', 'getColumns']
        );

        $jobDataFinisher->method('getFormValues')->willReturn([
            'companyField' => 'Acme Ltd.',
            'cityField' => 'Duckburg',
        ]);

        $jobDataFinisher->method('getColumns')->willReturn([
            'companyColumn' => [
                'mapOnFormField' => 'companyField'
            ],
            'cityColumn' => [
                'mapOnFormField' => 'cityField'
            ],
        ]);

        $actual = $jobDataFinisher->_call('prepareData');

        $expected = [
            'companyColumn' => 'Acme Ltd.',
            'cityColumn' => 'Duckburg',
        ];

        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function prepareDataProcessesStaticValueCorrectly(): void
    {
        /** @var $jobDataFinisher MockObject|AccessibleObjectInterface */
        $jobDataFinisher = $this->getAccessibleMock(
            JobDataFinisher::class,
            ['getFormValues', 'getColumns']
        );

        $jobDataFinisher->method('getFormValues')->willReturn([]);

        $jobDataFinisher->method('getColumns')->willReturn([
            'companyColumn' => [
                'staticValue' => 'Batman Inc.'
            ],
            'cityColumn' => [
                'staticValue' => 'Gotham City'
            ],
        ]);

        $actual = $jobDataFinisher->_call('prepareData');

        $expected = [
            'companyColumn' => 'Batman Inc.',
            'cityColumn' => 'Gotham City',
        ];

        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function writeFormDataToDatabaseDoesNothingWhenNoDataAvailable(): void
    {
        /** @var $jobDataFinisher MockObject|AccessibleObjectInterface */
        $jobDataFinisher = $this->getAccessibleMock(
            JobDataFinisher::class,
            ['prepareData', 'getTableUid']
        );

        $jobDataFinisher->method('prepareData')->willReturn([]);

        $jobDataFinisher->expects(self::never())->method('getTableUid');
    }

    /**
     * @test
     */
    public function getTableUidThrowsExceptionIfNonNumeric(): void
    {
        self::expectException(FinisherException::class);
        self::expectExceptionCode(1573824631);

        /** @var $jobDataFinisher MockObject|AccessibleObjectInterface */
        $jobDataFinisher = $this->getAccessibleMock(
            JobDataFinisher::class,
            ['parseOption']
        );

        $jobDataFinisher->method('parseOption')->willReturn('foo');

        $jobDataFinisher->_call('getTableUid');
    }

    /**
     * @test
     */
    public function getTableUidThrowsExceptionIfNegative(): void
    {
        self::expectException(FinisherException::class);
        self::expectExceptionCode(1573824632);

        /** @var $jobDataFinisher MockObject|AccessibleObjectInterface */
        $jobDataFinisher = $this->getAccessibleMock(
            JobDataFinisher::class,
            ['parseOption']
        );

        $jobDataFinisher->method('parseOption')->willReturn(-1);

        $jobDataFinisher->_call('getTableUid');
    }

    /**
     * @test
     */
    public function getTableUidReturnsTableUidAsIntWhenNumericStringGiven(): void
    {
        /** @var $jobDataFinisher MockObject|AccessibleObjectInterface */
        $jobDataFinisher = $this->getAccessibleMock(
            JobDataFinisher::class,
            ['parseOption']
        );

        $jobDataFinisher->method('parseOption')->willReturn('42');

        $actual = $jobDataFinisher->_call('getTableUid');

        self::assertSame(42, $actual);
    }

    /**
     * @test
     */
    public function getTableUidReturnsTableUidCorrectly(): void
    {
        /** @var $jobDataFinisher MockObject|AccessibleObjectInterface */
        $jobDataFinisher = $this->getAccessibleMock(
            JobDataFinisher::class,
            ['parseOption']
        );

        $jobDataFinisher->method('parseOption')->willReturn(3);

        $actual = $jobDataFinisher->_call('getTableUid');

        self::assertSame(3, $actual);
    }
}
