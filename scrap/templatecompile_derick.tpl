{*{include name=NS uri="design:b.tpl"}*}
{*{attribute_edit_gui attribute=$obj}*}
{*{my_gui attribute=$obj}*}
{*{my_gui_view view=first attribute=$obj}*}

{*
Sum, Sub and Mul tests
{sum( 4, 7, 6 )}
{9|sum( -12 )}

{sub( 8, 9, -2 )}

{mul( 1, 2, 3, 4, 5)}

{sum( 2, sub( 3, 2 ), 5, mul( 2, 7) )}



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
{let pi=3.141592654}
{abs( $pi )}
{/let}

{ceil( -9 )}
{ceil( 812.6 )}
{ceil( 4.1 )}
{ceil( -9.18312 )}
{let pi=3.141592654}
{ceil( $pi )}
{/let}

{floor( -9 )}
{floor( 812.6 )}
{floor( 4.1 )}
{floor( -9.18312 )}
{let pi=3.141592654}
{floor( $pi )}
{/let}

{round( -9 )}
{round( 812.6 )}
{round( 4.1 )}
{round( -9.18312 )}
{let pi=3.141592654}
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

{rot13( "test" )}
{md5( "test" )}
{sha1( "test" )}

{"test"|rot13}
{"test"|md5}
{"test"|sha1}

{"foo"|nl2br}
{nl2br("foo")}

{choose( 3, 'a', 'b', 'c', 'd', 'e' )}

{let select=4}
{choose( $select, 'a', 'b', 'c', 'd', 'e' )}
{/let}

{let select=1}
{choose( $select, 'a', 'b', 'c', 'd', 'e' )}
{/let}

{"foo"|concat( "bar" )}
{concat( "baz", "barbara" )}

{let indent=4}
{"test"|indent( $indent, 'tab' )}
{"test"|indent( $indent, 'space' )}
{"test"|indent( $indent, 'custom', 'foo ' )}
{/let}

{"test"|indent( 4, 'tab' )}
{"test"|indent( 4, 'space' )}
{"test"|indent( 4, 'custom', 'foo ' )}

{upfirst("derick rethans")}

{"Derick Rethans"|countwords}
{countwords( "Derick Rethans" )}
                                                                                                                                              
{let text="Derick Rethans"}
{$text|countwords}
{countwords( $text )}
{/let}


{"derick"|ord}
{ord( 'derick' )}

{let str='derick'}
{$str|ord}
{ord( $str )}
{/let}

{array( 99,98,100,102 )|chr}
{chr( array( 99,98,100,102 ) )}

{"foo"|pad( 16 )}
{pad( "foo", 16)}

{"bar"|pad( 16, '<' )}
{pad( "bar", 16, '<' )}

{let str='barbarella'}
{$str|pad( 16 )}
{pad( $str, 16)}

{$str|pad( 16, '<' )}
{pad( $str, 16, '<' )}
{/let}

{"Derick Rethans"|shorten( 8 )}
{"Derick Rethans"|shorten( 9 )}
{"Derick Rethans"|shorten( 10 )}
{"Derick Rethans"|shorten( 11 )}
{"Derick Rethans"|shorten( 12 )}
{"Derick Rethans"|shorten( 13 )}
{"Derick Rethans"|shorten( 14 )}

{let str="Derick Rethans"}
{$str|shorten( 15 )}
{$str|shorten( 16 )}

{$str|shorten( 8, '....' )}
{$str|shorten( 9, '....' )}
{$str|shorten( 10, '....' )}
{$str|shorten( 11, '....' )}
{/let}

{"Derick Rethans"|shorten( 12, '....' )}
{"Derick Rethans"|shorten( 13, '....' )}
{"Derick Rethans"|shorten( 14, '....' )}
{"Derick Rethans"|shorten( 15, '....' )}
{"Derick Rethans"|shorten( 16 )}

{"We    don't    need   no whitespace!"|simplify}
{"this____string__is___annoying"|simplify( "_" )}

{let str="Blah,   thiis   iis   annoyiing"}
{simplify( $str )}
{simplify( $str, 'i' )}
{$str|simplify( 'i' )}
{/let}


{"    Gizmo is not a gremlin.  "|trim}
{"    Gizmo is not a gremlin.  "|trim}
{"    Gizmo is not a gremlin.  "|trim(" .gremlin")}

{let str="	De kat krapt
"}
{$str|trim}
{$str|trim( "	D
" )}
{/let}

{"Hello world"|wrap}
{"Hello world"|wrap( 5 )}
{"Hello world"|wrap( 8 )}
{"Hello world"|wrap( 8, '-' )}
{"Hello_world"|wrap( 8, '-', 1)}
*}

{"foo <bar>"|wash}
{"foo <bar>"|wash( 'xhtml' )}

{let str="<a href='http://www.ez.no'>www.ez.no</a>"}
{$str|wash}
{$str|wash( 'xhtml' )}
{/let}

{"	<a href='http://www.ez.no'>www.ez.no</a>"|wash( 'pdf' )}

{let str="	<a href='http://www.ez.no'>www.ez.no</a>"}
{$str|wash( 'pdf' )}
{/let}


{"info@ez.no"|wash( 'email' )}

{let str="info@ez.no"}
{$str|wash( 'email' )}
{/let}


{let str="Info <info@ez.no>" type="xhtml"}
{$str|wash( $type )}
{/let}

{let str="Info <info@ez.no>" type="email"}
{$str|wash( $type )}
{/let}

{let str="Info <info@ez.no>" type="pdf"}
{$str|wash( $type )}
{/let}

END
