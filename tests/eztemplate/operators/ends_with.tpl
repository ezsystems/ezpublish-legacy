array( 1, 2, 3, 4 )|ends_with( 3, 4 )='{array( 1, 2, 3, 4 )|ends_with( 3, 4 )}'

{let kake=4}
array( 1, 2, 3, $kake )|ends_with( 3, 4 )='{array( 1, 2, 3, $kake )|ends_with( 3, 4 )}'
{/let}

array( 1, 2, 3, 4 )|ends_with( 4, 3 )='{array( 1, 2, 3, 4 )|ends_with( 4, 3 )}'

{let kake=4}
array( 1, 2, 3, $kake )|begins_with( 4, 3 )='{array( 1, 2, 3, $kake )|begins_with( 4, 3 )}'
{/let}

"Kake mann"|ends_with( "Kake" )='{"Kake mann"|ends_with( "Kake" )}'

{let arr="Kake mann"}
$arr|ends_with( "Kake" )='{$arr|ends_with( "Kake" )}'
{/let}

"Kake mann"|ends_with( "mann" )='{"Kake mann"|ends_with( "mann" )}'

{let arr="Kake mann"}
$arr|ends_with( "mann" )='{$arr|ends_with( "mann" )}'
{/let}