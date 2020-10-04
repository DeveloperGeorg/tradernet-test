<?php

namespace App\Http\Resources;

use App\Modules\DayRate\DayRateDiff;
use DayRate\DayRate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ApiDayRateDiff
 *
 * @property DayRateDiff $resource
 * @method float getAbsolute()
 * @method DayRate getTo()
 *
 * @package App\Http\Resources
 */
class ApiDayRateDiff extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $return = [];
        return array_merge($return, [
            'price' => $this->getTo()->getPrice(),
            'difference' => $this->getAbsolute()
        ]);
    }
}
