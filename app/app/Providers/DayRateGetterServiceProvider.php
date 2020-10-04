<?php

namespace App\Providers;

use App\Modules\DayRate\CacheProxyDayRateGetter;
use CbrDayRateSoap\DayRateGetter;
use CbrSimpleSoap\Client;
use DayRate\DayRateGetterInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Psr\SimpleCache\CacheInterface;
use Throwable;

class DayRateGetterServiceProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DayRateGetterInterface::class, function ($app) {
            $dayRateGetter = new DayRateGetter(new Client(
                config('app.wsdl'),
                [
                    'soap_version' => SOAP_1_2,
                    'exceptions' => true
                ]
            ));

            try {
                $cache = Cache::store();
                if ($cache instanceof CacheInterface) {
                    $dayRateGetter = new CacheProxyDayRateGetter(
                        $cache,
                        $dayRateGetter
                    );
                }
            } catch (Throwable $throwable) {
                Log::error($throwable->getMessage() . "\n" . $throwable->getTraceAsString());
            }

            return $dayRateGetter;
        });
    }

    public function provides()
    {
        return [DayRateGetterInterface::class];
    }
}
