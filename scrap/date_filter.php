<?php

$orig = 'l kl. %H';

$filtered = preg_replace( array( '/(?<!%)l/', '/(?<!%)H/' ),
                          array( '\l', '\H' ),
                          $orig );


print( $filtered . "\n" );

?>
