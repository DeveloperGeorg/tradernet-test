<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiDayRateDiff;
use App\Modules\DayRate\RateDiffGetterInterface as DayRateFacadeInterface;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $quoteCurrency = (string)$request->get('quoteCurrency');
        $baseCurrency = (string)$request->get('baseCurrency');
        $rateDiff = $this->dayRateFacade->getRateDiff(
            DateTime::createFromFormat('Y-m-d', $date),
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
