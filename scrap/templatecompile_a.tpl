{*{include name=NS uri="design:b.tpl"}*}
{*{attribute_edit_gui attribute=$obj}*}
{*{my_gui attribute=$obj}*}
{*{my_gui_view view=first attribute=$obj}*}

{*
Sum, Sub and Mul tests
{sum( 4, 7, 6 )}

{sub( 8, 9, -2 )}

{mul( 1, 2, 3, 4, 5)}

{sum( 2, sub( 3, 2 ), 5, mul( 2, 7) )}

{9|sum( -12 )}


Test of nested operators and variable lookup.

{let a=1}
    {sum( 4, sub( 10, sum( 1, mul( 2, 1 ) ) ), $a, 6 )}
{/let}



Min and Max tests

{max( 1, 9, 1, 3 )}
{min( 1, 9, -1, 3 )}
{8|max( -1, 2 )}

{let foo=12}
{max( 1, 9, 1, 3, $foo )}
{min( 1, 9, 1, 3, $foo )}
{/let}

Rounding functions

{abs( -9 )}
{abs( 812.6 )}
{abs( 4.1 )}
{abs( -9.18312 )}

{ceil( -9 )}
{ceil( 812.6 )}
{ceil( 4.1 )}
{ceil( -9.18312 )}

{floor( -9 )}
{floor( 812.6 )}
{floor( 4.1 )}
{floor( -9.18312 )}

{round( -9 )}
{round( 812.6 )}
{round( 4.1 )}
{round( -9.18312 )}

{let pi=3.141592654}
{abs( $pi )}
{ceil( $pi )}
{floor( $pi )}
{round( $pi )}
{/let}

Modulo
{mod( 11, 3 )}

{let foo=11}
{mod( $foo, 3 )}
{/let}

Int and Float casting
{int( 9.1231 )}
{float( 9 )}

{let pi=3.141592654}
{int( $pi )}
{float( $pi )}
{/let}

Roman stuff
{roman(1753)}
{let geboortejaar=1978)}
{roman($geboortejaar)}
{/let}


Dec and Inc
{90|dec()}
{90|dec(89)}
{dec( 89 )}

{inc( 123 )}
*}

Ge/Gt/Le/Lt

{41|gt( 8 )}
{lt( 8, 12 )}
{8|lt( 12 )}


{8|le( 8 )}
{8|ge( 8 )}

{8|ge( 12 )}

{let value=false()}
{and( 0, 0, 0, 0 )}
{and( 1, $value, 1, 1 )}
{and( 1, 1, 1, 1 )}

{or( 0, 0, 0, 0 )}
{or( 1, 0, 1, 1 )}
{or( 1, 1, 1, 1 )}
{/let}

{not( 1 )}
{45|not()}

{true()}
{false()}

{or( true(), false() )}

{choose( 3, 'a', 'b', 'c', 'd', 'e' )}

{let select=4}
{choose( $select, 'a', 'b', 'c', 'd', 'e' )}
{/let}

{let select=1}
{choose( $select, 'a', 'b', 'c', 'd', 'e' )}
{/let}

