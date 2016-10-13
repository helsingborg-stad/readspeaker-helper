<?php

namespace ReadSpeakerHelper;

class App
{
    private static $customerId = null;
    private static $playButtonId = 'readspeaker-play-button';
    private static $playerId = 'readspeaker-player-element';
    private static $readWrapperId = 'readspeaker-read';

    public function __construct()
    {
        add_filter('acf/settings/load_json', function ($paths) {
            $paths[] = READSPEAKERHELPER_PATH . '/acf-exports';
            return $paths;
        });

        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        if (!is_plugin_active('advanced-custom-fields-pro/acf.php')
            && !is_plugin_active('advanced-custom-fields/acf.php')
        ) {
            add_action('admin_notices', function () {
                echo '<div class="notice error"><p>' .
                        __('The ReadSpeaker Helper plugin requires you to have the <a href="http://www.advancedcustomfields.com/pro/" target="_blank">Advanced Custom Fields</a> plugin installed and activated.', 'readspeaker-helper') .
                     '</p></div>';
            });
            return;
        }

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

        if (get_field('readspeaker-helper-read-wrapper-id', 'option')) {
            self::$readWrapperId = get_field('readspeaker-helper-read-wrapper-id', 'option');
        }

        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

        switch (get_field('readspeaker-helper-placement', 'option')) {
            case 'the_content':
                add_filter('the_content', function ($content) {
                    global $wp_query;

                    if (!is_single() || !in_the_loop() || !is_main_query() || is_comment_feed()) {
                        return $content;
                    }

                    do_action('ReadSpeakerHelper/before_the_readspeaker');
                    return $this->getReadSpeakerTag() . '<div id="' . self::$readWrapperId . '">' . $content . '</div>';
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
        $classes = array_merge(array('readspeaker-play-button'), $classes);
        $classes = apply_filters('ReadSpeakerHelper/play_button_class', $classes);
        $classes = implode(' ', $classes);

        $playButton = '<a id="' . self::$playButtonId . '"  onclick="javascript:readpage(this.href, \'' . self::$playerId . '\'); return false;" href="//app-eu.readspeaker.com/cgi-bin/rsent?customerid=' . self::$customerId . '&amp;lang=' .get_locale(). '&amp;readid=' . self::$readWrapperId . '&amp;url=' . self::currentUrl() . '" class="' . $classes . '">' . __('Listen', 'readspeaker-helper') . '</a>';

        return apply_filters('ReadSpeakerPlayer/play_button', $playButton);
    }

    /**
     * Get the player element
     * @return string
     */
    public static function getPlayer()
    {
        $player = '<div id="' . self::$playerId . '" class="readspeaker-player-element rs_skip rs_preserve"></div>';
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
            '//f1-eu.readspeaker.com/script/' . self::$customerId . '/ReadSpeaker.js?pids=embhl',
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
            READSPEAKERHELPER_URL . '/dist/js/readspeaker-helper.dev.js',
            array('jquery'),
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
