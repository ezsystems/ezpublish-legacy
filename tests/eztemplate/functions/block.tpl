{let a=1 b=1 c=5 d=3 e=2 f=2}
{set-block variable=mainblock}{sum( $a, $b )} + {sub( $c, $d )} = {mul( $e, $f )}{/set-block}
{append-block variable=listblock}{"Two
lines"|break}{/append-block}
{append-block variable=listblock}{"And
three
lines"|break}{/append-block}

mainblock="{$mainblock}"
listblock="{$listblock|implode( "
--------
" )}"

{/let}
