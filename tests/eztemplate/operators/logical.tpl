{let value=false()}
Static optimization tests:
expr5.1:or( false(), 5 )='{or( false(), 5 )}'
expr1:and( false(), $value )='{and( false(), $value )}'
expr2:and( $value, false() )='{and( $value, false() )}'
expr3:and( true(), true() )='{and( true(), true() )}'
expr4:and( true(), $value )='{and( true(), $value )}'
expr5:or( true(), $value )='{or( true(), $value )}'
expr6:or( false(), true(), $value )='{or( false(), true(), $value )}'
expr7:or( $value, false() )='{or( $value, false() )}'
expr8:or( false(), $value )='{or( false(), $value )}'
expr9:or( false(), false() )='{or( false(), false() )}'

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
{let bool=true() bool2=false()}{* bool3 is set by logical.php *}
$bool|not|choose( 'false', 'true' )='{$bool|not|choose( 'false', 'true' )}'
$bool2|not|choose( 'false', 'true' )='{$bool2|not|choose( 'false', 'true' )}'
$bool3|not|choose( 'false', 'true' )='{$bool3|not|choose( 'false', 'true' )}'
{/let}

45|not()='{45|not()}'
$value|not()='{$value|not()}'
$value|not|not='{$value|not|not}'

{/let}
