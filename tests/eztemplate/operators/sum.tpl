{sum( 4, 7, 6 )}
{9|sum( -12 )}

{let page_count=6 current_page=0}
6={sum( $:page_count, $:current_page )}
7={sum( $:page_count, 1, $:current_page )}
7={sum( $:page_count, $:current_page, 1 )}
8={sum( $:page_count, 1, $:current_page, 1 )}
7={sum( 1, $:page_count, $:current_page )}
8={sum( 1, 1, $:page_count, $:current_page )}
3={sum( 1, 1, 1 )}
1={sum( 1 )}
{/let}
