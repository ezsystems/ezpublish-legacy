{array( 1, 2, 3, 4)|ends_with(3, 4)}

{let kake=4}
{array( 1, 2, 3, $kake)|ends_with( 3, 4) }
{/let}

{array( 1, 2, 3, 4)|ends_with(4, 3)}

{let kake=4}
{array( 1, 2, 3, $kake)|begins_with( 4, 3) }
{/let}