<?php

if (!function_exists('ReadSpeakerHelper_playButton')) {
    function ReadSpeakerHelper_playButton($classes = array())
    {
        echo \ReadSpeakerHelper\App::getPlayButton($classes);
    }
}

if (!function_exists('ReadSpeakerHelper_player')) {
    function ReadSpeakerHelper_player()
    {
        echo \ReadSpeakerHelper\App::getPlayer();
    }
}
