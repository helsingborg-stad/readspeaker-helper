<?php

namespace ReadSpeakerHelper;

class App
{
    public function __construct()
    {
        new \ReadSpeakerHelper\Options();

        if (is_array(get_field('readspeaker-helper-enable-posttypes', 'option'))) {
            $this->init();
        }
    }

    public function init()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {

    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        $posttypesEnabled = get_field('readspeaker-helper-enable-posttypes', 'option');
        if (!is_array($posttypesEnabled) || !in_array(get_post_type(), $posttypesEnabled)) {
            return;
        }

        wp_register_script(
            'readspeaker',
            'http://f1.eu.readspeaker.com/script/5507/ReadSpeaker.js?pids=embhl',
            array(),
            '1.0.0',
            get_field('readspeaker-helper-script-footer', 'option')
        );
        wp_enqueue_script('readspeaker');

        wp_register_script(
            'readspeaker-helper',
            READSPEAKERHELPER_URL . '/dist/js/readspeaker-helper.min.js',
            array('readspeaker'),
            '1.0.0',
            get_field('readspeaker-helper-script-footer', 'option')
        );

        wp_localize_script('readspeaker-helper', 'readspeakerHelper', array(
            'targetElement' => get_option('readspeaker-helper-target-selector', 'option'),
            'playerElement' => get_option('readspeaker-helper-player-selector', 'option'),
            'play' => 'Lyssna',
            'stop' => 'Sluta lyssna'
        ));

        wp_enqueue_script('readspeaker-helper');
    }
}
