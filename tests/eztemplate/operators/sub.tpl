{sub( 8, 9, -2 )}


{let page_count=6 current_page=0}
6={sub( $:page_count, $:current_page )}
5={sub( $:page_count, 1, $:current_page )}
5={sub( $:page_count, $:current_page, 1 )}
4={sub( $:page_count, 1, $:current_page, 1 )}
-5={sub( 1, $:page_count, $:current_page )}
-6={sub( 1, 1, $:page_count, $:current_page )}
-1={sub( 1, 1, 1 )}
1={sub( 1 )}
{/let}


Test of input + parameters:

{let a=2 b=3 c=4}
2|sub( 3, 4 )=-5: {2|sub( 3, 4 )}
$a|sub( $b, $c )=-5: {$a|sub( $b, $c )}
{/let}
