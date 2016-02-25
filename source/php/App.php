<?php

namespace ReadSpeakerHelper;

class App
{
    private static $customerId = null;
    private static $playButtonId = 'readspeaker-play-button';
    private static $playerId = 'readspeaker-player-element';

    public function __construct()
    {
        new \ReadSpeakerHelper\Options();

        if (is_array(get_field('readspeaker-helper-enable-posttypes', 'option'))) {
            $this->init();
        }
    }

    /**
     * Initialize the readspeaker
     * @return void
     */
    public function init()
    {
        self::$customerId = get_field('readspeaker-helper-customer-id', 'option');

        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

        switch (get_field('readspeaker-helper-placement', 'option')) {
            case 'the_content':
                add_filter('the_content', function ($content) {
                    do_action('ReadSpeakerHelper/before_the_readspeaker');
                    return $this->getReadSpeakerTag() . $content;
                });
                break;
        }
    }

    /**
     * Gets the full readspeaker tag with wrapper
     * @return string
     */
    public function getReadSpeakerTag()
    {
        // Readspeaker tag markup
        $readspeakerTag = '<div class="readspeaker-wrapper">';
        $readspeakerTag .= self::getPlayButton();
        $readspeakerTag .= self::getPlayer();
        $readspeakerTag .= '</div>';

        return apply_filters('ReadSpeakerHelper/readspeaker_tag', $readspeakerTag);
    }

    /**
     * Get the play button
     * @param  array  $classes
     * @return string
     */
    public static function getPlayButton($classes = array())
    {
        $classes = apply_filters('ReadSpeakerHelper/play_button_class', $classes);
        $classes = implode(' ', $classes);

        $playButton = '<a id="' . self::$playButtonId . '" onclick="javascript:readpage(this.href, \'' . self::$playerId . '\'); return false;" href="http://app.eu.readspeaker.com/cgi-bin/rsent?customerid=' . self::$customerId . '&amp;lang=sv_se&amp;readid=article&amp;url=' . self::currentUrl() . '" class="' . $classes . '">' . __('Listen', 'readspeaker-helper') . '</a>';

        return apply_filters('ReadSpeakerPlayer/play_button', $playButton);
    }

    /**
     * Get the player element
     * @return string
     */
    public static function getPlayer()
    {
        $player = '<div id="' . self::$playerId . '" class="rs_skip rs_preserve"></div>';
        return apply_filters('ReadSpeakerPlayer/player_element', $player);
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

        /**
         * Enqueue readspeaker script
         */
        wp_register_script(
            'readspeaker',
            'http://f1.eu.readspeaker.com/script/' . self::$customerId . '/ReadSpeaker.js?pids=embhl',
            array(),
            '1.0.0',
            get_field('readspeaker-helper-script-footer', 'option')
        );
        wp_enqueue_script('readspeaker');

        /**
         * Embed readspeaker helper script
         */
        wp_register_script(
            'readspeaker-helper',
            READSPEAKERHELPER_URL . '/dist/js/readspeaker-helper.min.js',
            array('readspeaker'),
            '1.0.0',
            get_field('readspeaker-helper-script-footer', 'option')
        );

        /**
         * Localization
         */
        wp_localize_script('readspeaker-helper', 'readspeakerHelper', array(
            'play' => __('Listen', 'readspeaker-helper'),
            'stop' => __('Stop listening', 'readspeaker-helper')
        ));

        wp_enqueue_script('readspeaker-helper');
    }

    public static function currentUrl()
    {
        global $wp;
        return home_url(add_query_arg(array(), $wp->request));
    }
}
