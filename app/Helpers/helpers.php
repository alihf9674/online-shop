<?php

use Morilog\Jalali\Jalalian;

function jalaliDate(int $date, string $format = '%A %d %B %Y'): string
{
    return Jalalian::forge($date)->format($format);
}
