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


{let request_uri_string='content/view/full/98' show_subtree='blog' show_subtree2='98'}
$request_uri_string|ends_with( $show_subtree )='{$request_uri_string|ends_with( $show_subtree )}'
$request_uri_string|ends_with( $show_subtree2 )='{$request_uri_string|ends_with( $show_subtree2 )}'
{/let}


{let request_uri_array=array( 'content', 'view', 'full', '98' ) show_subtree='blog' show_subtree2='98'}
$request_uri_array|ends_with( $show_subtree )='{$request_uri_array|ends_with( $show_subtree )}'
$request_uri_array|ends_with( $show_subtree2 )='{$request_uri_array|ends_with( $show_subtree2 )}'
{/let}
