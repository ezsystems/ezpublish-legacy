{array( 1, 2, 3, 4)|begins_with(1, 2)}

{let kake=4}
{array( 1, 2, 3, $kake)|begins_with( 1, 2) }
{/let}

{array( 1, 2, 3, 4)|begins_with(2, 1)}

{let kake=4}
{array( 1, 2, 3, $kake)|begins_with( 2, 1) }
{/let}

{"Kake mann"|begins_with( "Kake" )}

{let arr="Kake mann"}
{$arr|begins_with( "Kake" )}
{/let}

{"Kake mann"|begins_with( "mann" )}

{let arr="Kake mann"}
{$arr|begins_with( "mann" )}
{/let}