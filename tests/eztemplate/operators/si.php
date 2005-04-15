<?php

// the behaviour of 'number_format' function can
// differs for different versions of php
// We will compare results that will be obtained from
// php and template operator.
$exp_decimal = number_format( 1025 / 1000, 2, '.', ',' );
$exp_kilo = number_format( 1025 / 1000, 2, '.', ',' );

$tpl->setVariable( 'exp_decimal', "$exp_decimal kB" );
$tpl->setVariable( 'exp_kilo', "$exp_kilo kB" );

?>
