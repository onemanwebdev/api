<?php
    if( !array_key_exists( 'HTTP_ORIGIN', $_SERVER ) ) {
        $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
    }

    // RUN API
    require_once( 'core.php' );
    $api = new API ( $_SERVER, $_REQUEST );
    echo $api->runAPI();
?>
