{let list=array( '1', '2', '3', '4', '5', '6', '7', '8' )}
Loop eight times: 1 to 8

{section loop=$list}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times, offset 2: 3 to 8

{section loop=$list offset=2}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2: 6 to 1

{section loop=$list offset=2 reverse}
{$:key},{$:number},{$:index}: {$:item}

{/section}

====================================

Loop eight times, offset 2, max 5: 3 to 7

{section loop=$list offset=2 max=5}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2, max 5: 6 to 2

{section loop=$list offset=2 reverse max=5}
{$:key},{$:number},{$:index}: {$:item}

{/section}

====================================

Loop eight times, offset 2, max 5: 3 to 8, skip 4

{section loop=$list offset=2 max=5}
{section-exclude match=eq( $:item, 4 )}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2, max 5: 6 to 1, skip 4

{section loop=$list offset=2 reverse max=5}
{section-exclude match=eq( $:item, 4 )}
{$:key},{$:number},{$:index}: {$:item}

{/section}
{/let}