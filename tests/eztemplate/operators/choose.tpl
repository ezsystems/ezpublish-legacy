{choose( 3, 'a', 'b', 'c', 'd', 'e' )}

{let select=4}
{choose( $select, 'a', 'b', 'c', 'd', 'e' )}
{/let}

{let select=1}
{choose( $select, 'a', 'b', 'c', 'd', 'e' )}
{/let}
