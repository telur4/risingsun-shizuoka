<?php

function sanitize($data) {
    $clean = array();

    foreach( $data as $key => $value ) {
        $clean[$key] = htmlspecialchars( $value, ENT_QUOTES);
    }

    return $clean;
}
