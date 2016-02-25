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

    public function init()
    {
        self::$customerId = get_field('readspeaker-helper-customer-id', 'option');

        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

        add_filter('the_content', function ($content) {
            return $this->getReadSpeakerTag() . $content;
        });
    }

    public function getReadSpeakerTag()
    {
        // Readspeaker tag markup
        $readspeakerTag = '<div class="readspeaker-wrapper">';
        $readspeakerTag .= self::getPlayButton();
        $readspeakerTag .= self::getPlayer();
        $readspeakerTag .= '</div>';

        return $readspeakerTag;
    }

    public static function getPlayButton()
    {
        return '<a id="' . self::$playButtonId . '" onclick="javascript:readpage(this.href, \'' . self::$playerId . '\'); return false;" href="http://app.eu.readspeaker.com/cgi-bin/rsent?customerid=' . self::$customerId . '&amp;lang=sv_se&amp;readid=article&amp;url=' . self::currentUrl() . '">' . __('Listen', 'readspeaker-helper') . '</a>';
    }

    public static function getPlayer()
    {
        return '<div id="' . self::$playerId . '" class="rs_skip rs_preserve"></div>';
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
