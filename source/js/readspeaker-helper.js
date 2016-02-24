var ReadSpeakerHelper = {};

ReadSpeakerHelper.Player = (function ($) {

    var rspkrElm;

    var playButtonSelector = '#readspeaker-play-button';
    var playerSelector = '#readspeaker-player-element';

    function Player() {
        jQuery(document).ready(function() {
            ReadSpeaker.q(function() {
                rspkrElm = $rs.get(playButtonSelector);

                rspkr.c.addEvent('onUIClosePlayer', function() {
                    rspkrElm.innerHTML = 'Lyssna';
                    $rs.setAttr(rspkrElm, 'onclick', 'readpage(this.href, "' + playerSelector + '"); return false;');
                    rspkrElm.onclick = new Function('readpage(this.href, "' + playerSelector + '"); return false;');
                });

                rspkr.c.addEvent('onUIShowPlayer', function() {
                    rspkrElm.innerHTML = 'Sluta lyssna';
                    $rs.setAttr(rspkrElm, 'onclick', 'rspkr.ui.getActivePlayer().close(); return false;');
                    rspkrElm.onclick = new Function("rspkr.ui.getActivePlayer().close(); return false;");
                });
            });
        });
    }

    return new Player();

})(jQuery);
