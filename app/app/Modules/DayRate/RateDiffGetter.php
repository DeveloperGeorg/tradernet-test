<?php

namespace App\Modules\DayRate;

use DateInterval;
use DateTime;
use DayRate\Currency;
use DayRate\DayRate;
use DayRate\DayRateGetterInterface;
use InvalidArgumentException;

/**
 * Class Facade
 *
 * @package App\Modules\DayRate
 */
class RateDiffGetter implements RateDiffGetterInterface
{
    /**
     * @var DayRateGetterInterface
     */
    private $dayRateGetter;

    /**
     * Facade constructor.
     *
     * @param DayRateGetterInterface $dayRateGetter
     */
    public function __construct(DayRateGetterInterface $dayRateGetter)
    {
        $this->dayRateGetter = $dayRateGetter;
    }

    /**
     * @inheritDoc
     */
    public function getRateDiff(
        DateTime $dateTime,
        string $quoteCurrency,
        ?string $baseCurrency = 'RUR',
        ?DateTime $compareDateTime = null
    ): ?DayRateDiff {
        if (strlen($quoteCurrency) <= 0) {
            throw new InvalidArgumentException('Quote Currency required');
        }
        if ($baseCurrency === null || strlen($baseCurrency) <= 0) {
            $baseCurrency = 'RUR';
        }
        $firstRate = $this->getRate(
            $dateTime,
            $quoteCurrency,
            $baseCurrency
        );
        if ($firstRate) {
            if ($compareDateTime === null) {
                $compareDateTime = clone $dateTime;
                $compareDateTime->sub(new DateInterval('P1D'));
            }
            $secondRate = $this->getRate(
                $compareDateTime,
                $quoteCurrency,
                $baseCurrency
            );

            return new DayRateDiff($secondRate, $firstRate);
        } else {
            return null;
        }
    }

    /**
     * @param DateTime $dateTime
     * @param string $quoteCurrency
     * @param string $baseCurrency
     *
     * @return DayRate|null
     */
    private function getRate(DateTime $dateTime, string $quoteCurrency, string $baseCurrency = 'RUR'): ?DayRate
    {
        return $this->dayRateGetter->get(
            $dateTime,
            new Currency($quoteCurrency),
            new Currency($baseCurrency)
        );
    }
}
