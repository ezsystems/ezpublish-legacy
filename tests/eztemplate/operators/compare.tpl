array( 1, 2, 3, 4 )|compare( array( 1, 2, 3, 4 ) )='{array( 1, 2, 3, 4 )|compare( array( 1, 2, 3, 4 ) )}'

{let arr=array( 1, 2, 3, 4 )}
$arr|compare($arr)='{$arr|compare($arr)}'
{/let}

array( 1, 2, 3, 5 )|compare( array( 1, 2, 3, 4 ) )='{array( 1, 2, 3, 5 )|compare( array( 1, 2, 3, 4 ) )}'

{let arr=array( 1, 2, 3, 4 )}
array( 1, 2, 3 )|compare( $arr )='{array( 1, 2, 3 )|compare( $arr )}'
$arr|compare( array( 1, 2, 3 ) )='{$arr|compare( array( 1, 2, 3 ) )}'
{/let}

"str"|compare( "str" )='{"str"|compare( "str" )}'

{let str="str"}
$str|compare( $str )='{$str|compare( $str )}'
{/let}

"str2"|compare( "str" )='{"str2"|compare( "str" )}'

{let str="str"}
$str|compare( "str2" )='{$str|compare( "str2" )}'
{/let}
