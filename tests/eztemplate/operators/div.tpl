{div( 120, 5, 4, 3, 2, 1 )}


{let page_count=8 current_page=1}
8={div( $:page_count, $:current_page )}
4={div( $:page_count, 2, $:current_page )}
4={div( $:page_count, $:current_page, 2 )}
2={div( $:page_count, 2, $:current_page, 2 )}
2={div( $:page_count, $:current_page, 2, 2 )}
2={div( 64, $:page_count, $:current_page, 2, 2 )}
2={div( 128, 2, $:page_count, $:current_page, 2, 2 )}
0.25={div( 2, $:page_count, $:current_page )}
0.125={div( 2, 2, $:page_count, $:current_page )}
0.5={div( 2, 2, 2 )}
2={div( 2 )}
{/let}
