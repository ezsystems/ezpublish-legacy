{"We    don't    need   no whitespace!"|simplify}
{"this____string__is___annoying"|simplify( "_" )}

{let str="Blah,   thiis   iis   annoyiing"}
{simplify( $str )}
{simplify( $str, 'i' )}
{$str|simplify( 'i' )}
{/let}
