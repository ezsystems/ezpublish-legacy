{mul( 1, 2, 3, 4, 5)}


{let page_count=6 current_page=1}
6={mul( $:page_count, $:current_page )}
12={mul( $:page_count, 2, $:current_page )}
12={mul( $:page_count, $:current_page, 2 )}
24={mul( $:page_count, 2, $:current_page, 2 )}
12={mul( 2, $:page_count, $:current_page )}
24={mul( 2, 2, $:page_count, $:current_page )}
8={mul( 2, 2, 2 )}
2={mul( 2 )}
{/let}
