<?php

if (!function_exists('ReadSpeakerHelper_playButton')) {
    function ReadSpeakerHelper_playButton($classes = array())
    {
        do_action('ReadSpeakerHelper/before_the_play_button');
        echo \ReadSpeakerHelper\App::getPlayButton($classes);
    }
}
