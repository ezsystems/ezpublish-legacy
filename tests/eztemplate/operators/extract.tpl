{let arr=array( 1, 2, 3, 4, 5, 6 )}
{$arr|extract( 2 )|implode( ',' )}
{$arr|extract( 2, 2 )|implode( ',' )}
{$arr|extract( 4, 4 )|implode( ',' )}
{$arr|extract_left( 3 )|implode( ',' )}
{$arr|extract_right( 3 )|implode( ',' )}

{/let}

{let string="123456"}

{$string|extract( 2 )}
{$string|extract( 2, 2 )}
{$string|extract( 4, 4 )}
{$string|extract_left( 3 )}
{$string|extract_right( 3 )}

{/let}
