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
