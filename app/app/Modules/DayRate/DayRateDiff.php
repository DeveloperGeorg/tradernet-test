<?php

namespace App\Modules\DayRate;

use DayRate\DayRate;

class DayRateDiff
{
    /**
     * @var DayRate
     */
    private $from;
    /**
     * @var DayRate
     */
    private $to;

    /**
     * DayRateDiff constructor.
     *
     * @param DayRate $from
     * @param DayRate $to
     */
    public function __construct(DayRate $from, DayRate $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return float
     */
    public function getAbsolute(): float
    {
        return $this->getTo()->getPrice() - $this->getFrom()->getPrice();
    }

    /**
     * @return DayRate
     */
    public function getFrom(): DayRate
    {
        return $this->from;
    }

    /**
     * @param DayRate $from
     *
     * @return DayRateDiff
     */
    public function setFrom(DayRate $from): DayRateDiff
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return DayRate
     */
    public function getTo(): DayRate
    {
        return $this->to;
    }

    /**
     * @param DayRate $to
     *
     * @return DayRateDiff
     */
    public function setTo(DayRate $to): DayRateDiff
    {
        $this->to = $to;
        return $this;
    }
}
