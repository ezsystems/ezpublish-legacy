{let id="def"}
{section var=item loop=array( 1, 2, 3, 4, 5, 10, 20 )}
{debug-accumulator id="abc" name="AbC"}
{let first=1 second="2" third=array( 1, 5, "test" )}
{sum( $item, $first, $second)} != {mul( sum( $item, $first, $second ), $third[1] )}

{/let}
{/debug-accumulator}
{/section}

{section var=item loop=array( 1, 2, 3, 4, 5, 10, 20 )}
{debug-accumulator id=$id name="dEf"}
{let first=1 second="2" third=array( 1, 5, "test" )}
{sum( $item, $first, $second)} != {mul( sum( $item, $first, $second ), $third[1] )}

{/let}
{/debug-accumulator}
{/section}
{/let}
