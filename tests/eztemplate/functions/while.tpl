{* normal loop *}
{def $i=5}
{while $i}
i={$i}

{set $i=dec( $i )}
{/while}
===============================================================
{* loop that should not be executed due to false condition *}
{while 0}
oops
{/while}
===============================================================
{* test {break}, {continue}, {skip} *}
{set $i=1}
{while le( $i, 10 )}
{delimiter} :: {/delimiter}
{if eq( $i, 3 )}
{set $i=inc( $i )}
{continue}
{elseif eq( $i, 5)}
{set $i=inc( $i )}
{skip}
{/if}
{$i}
{if eq( $i, 7)}
{break}
{/if}
{set $i=inc( $i )}
{/while}

===============================================================
{* test sequence parameter *}
{while $i sequence array( 'dark', 'light', 'white' ) as $color}
One more {$color} beer, please.

{set $i=dec( $i )}
{/while}
