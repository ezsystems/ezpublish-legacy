array( 1, 2, 3, 4 )|begins_with( 1, 2 )='{array( 1, 2, 3, 4 )|begins_with( 1, 2 )}'

{let kake=4}
array( 1, 2, 3, $kake )|begins_with( 1, 2 )='{array( 1, 2, 3, $kake )|begins_with( 1, 2 )}'
{/let}

array( 1, 2, 3, 4 )|begins_with( 2, 1 )='{array( 1, 2, 3, 4 )|begins_with( 2, 1 )}'

{let kake=4}
array( 1, 2, 3, $kake )|begins_with( 2, 1 )='{array( 1, 2, 3, $kake )|begins_with( 2, 1 )}'
{/let}

"Kake mann"|begins_with( "Kake" )='{"Kake mann"|begins_with( "Kake" )}'

{let arr="Kake mann"}
$arr|begins_with( "Kake" )='{$arr|begins_with( "Kake" )}'
{/let}

"Kake mann"|begins_with( "mann" )='{"Kake mann"|begins_with( "mann" )}'

{let arr="Kake mann"}
$arr|begins_with( "mann" )='{$arr|begins_with( "mann" )}'
{/let}


{let request_uri_string='content/view/full/98' show_subtree='blog' show_subtree2='content'}
$request_uri_string|begins_with( $show_subtree )='{$request_uri_string|begins_with( $show_subtree )}'
$request_uri_string|begins_with( $show_subtree2 )='{$request_uri_string|begins_with( $show_subtree2 )}'
{/let}


{let request_uri_array=array( 'content', 'view', 'full', '98' ) show_subtree='blog' show_subtree2='content'}
$request_uri_array|begins_with( $show_subtree )='{$request_uri_array|begins_with( $show_subtree )}'
$request_uri_array|begins_with( $show_subtree2 )='{$request_uri_array|begins_with( $show_subtree2 )}'
{/let}
