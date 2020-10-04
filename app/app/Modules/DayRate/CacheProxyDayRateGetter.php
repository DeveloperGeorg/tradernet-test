<?php

namespace App\Modules\DayRate;

use DateTime;
use DayRate\Currency;
use DayRate\DayRate;
use DayRate\DayRateGetterInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CacheProxyDayRateGetter implements DayRateGetterInterface
{
    /**
     * @var CacheInterface
     */
    private $cache;
    /**
     * @var string
     */
    private $cacheKeyPattern = 'day_rate.{date}.{quote}.{base}';
    /**
     * @var DayRateGetterInterface
     */
    private $dayRateGetter;

    /**
     * CacheProxyDayRateGetter constructor.
     *
     * @param CacheInterface $cache
     * @param DayRateGetterInterface $dayRateGetter
     * @param string|null $cacheKeyPattern
     */
    public function __construct(
        CacheInterface $cache,
        DayRateGetterInterface $dayRateGetter,
        ?string $cacheKeyPattern = null
    ) {
        $this->setCache($cache);
        $this->setDayRateGetter($dayRateGetter);
        if ($cacheKeyPattern !== null) {
            $this->cacheKeyPattern = $cacheKeyPattern;
        }
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     */
    public function get(DateTime $dateTime, Currency $quoteCurrency, ?Currency $baseCurrency = null): ?DayRate
    {
        $cacheKey = $this->getKey($dateTime, $quoteCurrency, $baseCurrency);
        $returnDayRate = $this->getCache()->get($cacheKey);
        if ($returnDayRate === null || !($returnDayRate instanceof DayRate)) {
            $returnDayRate = $this->getDayRateGetter()->get($dateTime, $quoteCurrency, $baseCurrency);
            $this->getCache()->set($cacheKey, $returnDayRate);
        }

        return $returnDayRate;
    }

    /**
     * @param DateTime $dateTime
     * @param Currency $quoteCurrency
     * @param Currency|null $baseCurrency
     *
     * @return string
     */
    private function getKey(DateTime $dateTime, Currency $quoteCurrency, ?Currency $baseCurrency = null): string
    {
        $key = $this->cacheKeyPattern;
        $key = str_replace('{date}', $dateTime->format('Y-m-d'), $key);
        $key = str_replace('{quote}', $quoteCurrency->getCode(), $key);
        if ($baseCurrency !== null) {
            $key = str_replace('{base}', $baseCurrency->getCode(), $key);
        }
        return $key;
    }

    /**
     * @return CacheInterface
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheInterface $cache
     *
     * @return CacheProxyDayRateGetter
     */
    public function setCache(CacheInterface $cache): CacheProxyDayRateGetter
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @return DayRateGetterInterface
     */
    public function getDayRateGetter(): DayRateGetterInterface
    {
        return $this->dayRateGetter;
    }

    /**
     * @param DayRateGetterInterface $dayRateGetter
     *
     * @return CacheProxyDayRateGetter
     */
    public function setDayRateGetter(DayRateGetterInterface $dayRateGetter): CacheProxyDayRateGetter
    {
        $this->dayRateGetter = $dayRateGetter;
        return $this;
    }
}
