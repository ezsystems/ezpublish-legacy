{let value=false()}
1|lt( 0 )='{1|lt( 0 )}'
0|lt( 0 )='{0|lt( 0 )}'
0|lt( 1 )='{0|lt( 1 )}'

1|gt( 0 )='{1|gt( 0 )}'
0|gt( 0 )='{0|gt( 0 )}'
0|gt( 1 )='{0|gt( 1 )}'

1|le( 0 )='{1|le( 0 )}'
0|le( 0 )='{0|le( 0 )}'
0|le( 1 )='{0|le( 1 )}'

1|ge( 0 )='{1|ge( 0 )}'
0|ge( 0 )='{0|ge( 0 )}'
0|ge( 1 )='{0|ge( 1 )}'

and( 0, 0, 0, 0 )='{and( 0, 0, 0, 0 )}'
and( 1, $value, 1, 1 )='{and( 1, $value, 1, 1 )}'
and( 1, 1, 1, 1 )='{and( 1, 1, 1, 1 )}'

or( 0, 0, 0, 0 )='{or( 0, 0, 0, 0 )}'
or( 1, 0, 1, 1 )='{or( 1, 0, 1, 1 )}'
or( 1, 1, 1, 1 )='{or( 1, 1, 1, 1 )}'

eq( 0, 0 )='{eq( 0, 0 )}'
eq( 1, 1 )='{eq( 1, 1 )}'
eq( 0, $value )='{eq( 0, $value )}'
eq( 1, $value )='{eq( 1, $value )}'

ne( 0, 0 )='{ne( 0, 0 )}'
ne( 1, 1 )='{ne( 1, 1 )}'
ne( 0, $value )='{ne( 0, $value )}'
ne( 1, $value )='{ne( 1, $value )}'

not( 1 )='{not( 1 )}'
not( $value )='{not( $value )}'
45|not()='{45|not()}'
$value|not()='{$value|not()}'
$value|not|not='{$value|not|not}'

{/let}
