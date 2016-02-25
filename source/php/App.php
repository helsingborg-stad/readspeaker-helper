<?php

namespace ReadSpeakerHelper;

class App
{

    private $customerId = null;
    private $playButtonId = 'readspeaker-play-button';
    private $playerId = 'readspeaker-player-element';

    public function __construct()
    {
        new \ReadSpeakerHelper\Options();

        if (is_array(get_field('readspeaker-helper-enable-posttypes', 'option'))) {
            $this->init();
        }
    }

    public function init()
    {
        $this->customerId = get_field('readspeaker-helper-customer-id', 'option');

        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

        add_filter('the_content', function ($content) {
            return $this->getReadSpeakerTag() . $content;
        });
    }

    public function getReadSpeakerTag()
    {
        // Readspeaker tag markup
        $readspeakerTag = '<div class="readspeaker-wrapper">';
        $readspeakerTag .= '<a id="' . $this->playButtonId . '" onclick="javascript:readpage(this.href, \'' . $this->playerId . '\'); return false;" href="http://app.eu.readspeaker.com/cgi-bin/rsent?customerid=' . $this->customerId . '&amp;lang=sv_se&amp;readid=article&amp;url=' . $this->currentUrl() . '">Lyssna</a>';
        $readspeakerTag .= '<div id="' . $this->playerId . '" class="rs_skip rs_preserve"></div>';
        $readspeakerTag .= '</div>';

        return $readspeakerTag;
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
            'http://f1.eu.readspeaker.com/script/' . $this->customerId . '/ReadSpeaker.js?pids=embhl',
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
            'play' => 'Lyssna',
            'stop' => 'Sluta lyssna'
        ));

        wp_enqueue_script('readspeaker-helper');
    }

    public function currentUrl()
    {
        global $wp;
        return home_url(add_query_arg(array(), $wp->request));
    }
}
