<?php

// the precision of 'float' datatype  can
// differs for different versions of php
// We will compare results that will be obtained from
// php and template operator.
$exp_div = 2/3/4;
$tpl->setVariable( 'exp_div_value', "div_value_$exp_div" );

?>
