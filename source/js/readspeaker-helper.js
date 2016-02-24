var ReadSpeakerHelper = {};

ReadSpeakerHelper.Player = (function ($) {

    var rspkrElm;

    function Player() {
        jQuery(document).ready(function() {
            ReadSpeaker.q(function() {
                rspkrElm = $rs.get(readspeakerHelper.targetElement);

                rspkr.c.addEvent('onUIClosePlayer', function() {
                    rspkrElm.innerHTML = readspeakerHelper.play;
                    $rs.setAttr(rspkrElm, 'onclick', 'readpage(this.href, "' + readspeakerHelper.playerSelector + '"); return false;');
                    rspkrElm.onclick = new Function('readpage(this.href, "' + readspeakerHelper.playerSelector + '"); return false;');
                });

                rspkr.c.addEvent('onUIShowPlayer', function() {
                    rspkrElm.innerHTML = readspeakerHelper.stop;
                    $rs.setAttr(rspkrElm, 'onclick', 'rspkr.ui.getActivePlayer().close(); return false;');
                    rspkrElm.onclick = new Function("rspkr.ui.getActivePlayer().close(); return false;");
                });
            });
        });
    }

    return new Player();

})(jQuery);
