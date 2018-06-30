<?php
/**
 * Created by PhpStorm.
 * User: 0300962
 * Date: 22/06/2018
 * Time: 12:58
 */
?>
<script>
    var version = 1;

    if(!!window.EventSource) {
        var msgSource = new EventSource("Scripts/version.php");
        msgSource.onopen = function(event) {
            if (event.data != version) {
                version = event.data;
                update();
            }
        }
    }
</script>
