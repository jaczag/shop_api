<?php
namespace App\Traits;
use Carbon\Carbon;

trait DateTimeCast {
    /**
     * convert timestamps to human readable dates
     * @param Carbon|null $date
     * @return string|null
     */
    public function convertTimestamp(?Carbon $date): ?string
    {
        return $date?->toDateTimeString();
    }
}
