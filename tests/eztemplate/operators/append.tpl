Append:
{array( 1, 2, 3, 4 )|append( 5, 6, 7 )|implode( ", " )}

{let arr=array( 1, 2, 3, 4 )
     arr2=5
     arr3=6
     arr4=7}
{$arr|append($arr2, $arr3, $arr4)|implode( ", " )}
{/let}

{"a b c "|append( "d ", "e" )}

{let str="a b c "}
{$str|append( $str )}
{/let}

Prepend:
{array( 1, 2, 3, 4 )|prepend( 5, 6, 7 )|implode( ", " )}

{let arr=array( 1, 2, 3, 4 )
     arr2=5
     arr3=6
     arr4=7}
{$arr|prepend($arr2, $arr3, $arr4)|implode( ", " )}
{/let}

{"a b c"|prepend( "d " )}

{let str="a b c "}
{$str|prepend( $str )}
{/let}