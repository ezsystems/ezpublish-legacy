{"We    don't    need   no whitespace!"|simplify}
{"this____string__is___annoying"|simplify( "_" )}

{let str="Blah,   thiis   iis   annoyiing"}
{$str|simplify}
{$str|simplify( 'i' )}
{$str|simplify( 'n' )}
{/let}
