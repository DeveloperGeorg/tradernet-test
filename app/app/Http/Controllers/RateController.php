<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiDayRateDiff;
use App\Modules\DayRate\RateDiffGetterInterface as DayRateFacadeInterface;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;

class RateController extends Controller
{
    /**
     * @var DayRateFacadeInterface
     */
    private $dayRateFacade;

    /**
     * Create a new controller instance.
     *
     * @param DayRateFacadeInterface $dayRateFacade
     */
    public function __construct(DayRateFacadeInterface $dayRateFacade)
    {
        $this->dayRateFacade = $dayRateFacade;
    }

    //
    public function getOne(Request $request)
    {
        $date = (string)$request->get('date');
        if (strlen($date) <= 0) {
            throw new InvalidArgumentException('date required');
        }
        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        if (!($dateTime instanceof DateTime) || $dateTime->format('Y-m-d') !== $date) {
            throw new InvalidArgumentException('Wrong pattern date (Y-m-d)');
        }
        $quoteCurrency = (string)$request->get('quoteCurrency');
        $baseCurrency = (string)$request->get('baseCurrency');
        $rateDiff = $this->dayRateFacade->getRateDiff(
            $dateTime,
            $quoteCurrency,
            $baseCurrency
        );

        $apiRateDiff = response('', Response::HTTP_NO_CONTENT);
        if ($rateDiff) {
            $apiRateDiff = new ApiDayRateDiff($rateDiff);
        }

        return $apiRateDiff;
    }
}
