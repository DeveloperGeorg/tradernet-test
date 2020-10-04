<?php

namespace App\Modules\DayRate;

use DateTime;

/**
 * Interface FacadeInterface
 *
 * @package App\Modules\DayRate
 */
interface RateDiffGetterInterface
{

    /**
     * @param DateTime $dateTime
     * @param string $quoteCurrency
     * @param string|null $baseCurrency
     * @param DateTime|null $compareDateTime
     *
     * @return DayRateDiff|null
     */
    public function getRateDiff(
        DateTime $dateTime,
        string $quoteCurrency,
        ?string $baseCurrency = 'RUR',
        ?DateTime $compareDateTime = null
    ): ?DayRateDiff;
}
