var ReadSpeakerHelper = {};

ReadSpeakerHelper.Player = (function ($) {

    var rspkrElm;

    function Player() {
        jQuery(document).ready(function() {
            ReadSpeaker.q(function() {
                rspkrElm = $rs.get('#listen');
                rspkr.c.addEvent('onUIShowPlayer', function() {
                    rspkrElm.innerHTML = 'Sluta lyssna';
                    $rs.setAttr(rspkrElm, 'onclick', 'rspkr.ui.getActivePlayer().close(); return false;');
                    rspkrElm.onclick = new Function("rspkr.ui.getActivePlayer().close(); return false;");
                });
                rspkr.c.addEvent('onUIClosePlayer', function() {
                    rspkrElm.innerHTML = 'Lyssna';
                    $rs.setAttr(rspkrElm, 'onclick', 'readpage(this.href, "read-speaker-player"); return false;');
                    rspkrElm.onclick = new Function('readpage(this.href, "read-speaker-player"); return false;');
                });
            });
        });
    }

    return new Player();

})(jQuery);
