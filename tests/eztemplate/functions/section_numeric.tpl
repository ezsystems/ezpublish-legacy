Loop eight times, offset 2: 3 to 8

{section loop=8 offset=2}
{$:item}

{/section}
------------------------------------
Loop eight times negative, offset 2: -3 to -8

{section loop=-8 offset=2}
{$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2: 8 to 3

{section loop=8 offset=2 reverse}
{$:item}

{/section}
------------------------------------
Loop eight times negative revers, offset 2: -8 to -3

{section loop=-8 offset=2 reverse}
{$:item}

{/section}

====================================

Loop eight times, offset 2, max 5: 3 to 7

{section loop=8 offset=2 max=5}
{$:item}

{/section}
------------------------------------
Loop eight times negative, offset 2, max 5: -3 to -7

{section loop=-8 offset=2 max=5}
{$:item}

{/section}
------------------------------------
Loop eight times reverse, offset 2, max 5: 8 to 4

{section loop=8 offset=2 reverse max=5}
{$:item}

{/section}
------------------------------------
Loop eight times negative reverse, offset 2, max 5: -8 to -4

{section loop=-8 offset=2 reverse max=5}
{$:item}

{/section}
