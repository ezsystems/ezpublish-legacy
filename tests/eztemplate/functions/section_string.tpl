{let str='abcdefgh'}
Loop eight times: a to h

{section loop=$str}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times, offset 2: c to h

{section loop=$str offset=2}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2: f to a

{section loop=$str offset=2 reverse}
{$:key},{$:number},{$:index}: {$:item}

{/section}

====================================

Loop eight times, offset 2, max 5: c to g

{section loop=$str offset=2 max=5}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2, max 5: f to b

{section loop=$str offset=2 reverse max=5}
{$:key},{$:number},{$:index}: {$:item}

{/section}

====================================

Loop eight times, offset 2, max 5: c to h, skip d

{section loop=$str offset=2 max=5}
{section-exclude match=eq( $:item, 'd' )}
{$:key},{$:number},{$:index}: {$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2, max 5: f to a, skip d

{section loop=$str offset=2 reverse max=5}
{section-exclude match=eq( $:item, 'd' )}
{$:key},{$:number},{$:index}: {$:item}

{/section}
{/let}