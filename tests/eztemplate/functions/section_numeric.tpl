Loop eight times: 1 to 8

{section loop=8}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times, offset 2: 3 to 8

{section loop=8 offset=2}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times negative, offset 2: -3 to -8

{section loop=-8 offset=2}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2: 6 to 1

{section loop=8 offset=2 reverse}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times negative revers, offset 2: -6 to -1

{section loop=-8 offset=2 reverse}
{$:key},{$:number},{$:index}: {$:item}

{/section}

====================================

Loop eight times, offset 2, max 5: 3 to 7

{section loop=8 offset=2 max=5}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times negative, offset 2, max 5: -3 to -7

{section loop=-8 offset=2 max=5}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2, max 5: 6 to 2

{section loop=8 offset=2 reverse max=5}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times negative reverse, offset 2, max 5: -6 to -2

{section loop=-8 offset=2 reverse max=5}
{$:key},{$:number},{$:index}: {$:item}

{/section}

====================================

Loop eight times, offset 2, max 5: 3 to 8, skip 4

{section loop=8 offset=2 max=5}
{section-exclude match=eq( $:item, 4 )}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times negative, offset 2, max 5: -3 to -8, skip -4

{section loop=-8 offset=2 max=5}
{section-exclude match=eq( $:item, -4 )}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2, max 5: 6 to 1, skip 4

{section loop=8 offset=2 reverse max=5}
{section-exclude match=eq( $:item, 4 )}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times negative reverse, offset 2, max 5: -6 to -1, skip -4

{section loop=-8 offset=2 reverse max=5}
{section-exclude match=eq( $:item, -4 )}
{$:key},{$:number},{$:index}: {$:item}

{/section}
