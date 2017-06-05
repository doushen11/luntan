<?php
class Thumbnail {
    function convert($input, $output) {
        $command = '/usr/local/bin/ffmpeg -i ' . $input. ' -y -f image2 -ss 08.010 -t 0.001 -s 350x240 ' . $output;
        LogUtil::info('ffmpeg command-------'.$command);
        $result = shell_exec($command);
        LogUtil::info('ffmpeg result-------'.$result);
    }
}