<?php
class Distance {
    function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000;
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        $result=round($calculatedDistance);
        return $result;
//         if($result<1000){
//             return $result."m";
//         }else{
//             return round($result/1000,2)."km";
//         }
    }
}