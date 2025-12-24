<?php
namespace App\Helpers;

class LemburHelper
{
    public static function hitungJam($start, $end)
    {
        $start = strtotime($start);
        $end = strtotime($end);
        $diff = $end - $start;
        $jam = floor($diff / 3600);
        $menit = floor(($diff % 3600) / 60);
        if ($jam < 0) $jam += 24;
        return $menit === 0 ? "$jam Jam" : "$jam Jam $menit Menit";
    }
    public static function getJam($start, $end)
    {
        $start = strtotime($start);
        $end = strtotime($end);
        $diff = $end - $start;
        $jam = $diff / 3600;
        if ($jam < 0) $jam += 24;
        return $jam;
    }
}
