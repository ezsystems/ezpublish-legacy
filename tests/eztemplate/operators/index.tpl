{let node=hash( object, hash( author_array, array( hash( id, 1 ), hash( id, 2 ), hash( id, 5 ) ) ) )}

$node.object.author_array[0]={$node.object.author_array[0]|implode( ',' )}
$node.object.author_array[1][id]={$node.object.author_array[1][id]}

{/let}
