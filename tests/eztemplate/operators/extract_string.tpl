Input: static, offset=static, length=static
{"123456"|extract( 2 )}
{"123456"|extract( 2, 2 )}
{"123456"|extract( 4, 4 )}
{"123456"|extract_left( 3 )}
{"123456"|extract_right( 3 )}
===============================================
Input: non-static, offset=static, length=static
{let string="123456"}

{$string|extract( 2 )}
{$string|extract( 2, 2 )}
{$string|extract( 4, 4 )}
{$string|extract_left( 3 )}
{$string|extract_right( 3 )}

{/let}
===============================================
Input: non-static, offset=non-static, length=static
{let string="123456" offs=2}

{$string|extract( $offs )}
{$string|extract( $offs, 2 )}
{$string|extract( sum( $offs, 2 ), 4 )}
{$string|extract_left( $offs|inc )}
{$string|extract_right( $offs|inc )}

{/let}
===============================================
Input: non-static, offset=non-static, length=non-static
{let string="123456" offs=2 len=2}

{$string|extract( $offs )}
{$string|extract( $offs, $len )}
{$string|extract( sum( $offs, 2 ), sum( $len, 2 ) )}
{$string|extract_left( $offs|inc )}
{$string|extract_right( $offs|inc )}

{/let}
===============================================
