{section name=NS1 loop=array( hash( list, array( 1, 2, 5 ) ),
                              hash( list, array( 'a', 5, 10 ) ),
                              hash( list, array( 5, 10, 20 ) ) )}
{$:key}:
list: 
{section name=NS2 loop=$:item.list}
{$:item}
{delimiter},{/delimiter}
{/section}
{delimiter}



{/delimiter}
{/section}


{* Tests if a double loop works with the second loop having the iterator of the first as parameter *}
{let nodes_children=hash( 'o', array(),
                          269, array( 270, 271 ),
                          270, array(),
                          271, array() )}
{section var=child loop=$nodes_children}
nodesChildren['node_{$child.key}'] = [

{section var=itm loop=$child}
 'node_{$itm}'
{delimiter},{/delimiter}
{/section}
 ];

{/section}
{/let}
