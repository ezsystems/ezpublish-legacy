{* simple case *}
{foreach array( 1, 2, 3 ) as $i}
i={$i}

{/foreach}
=====================================
{* using key_var and break *}
{foreach hash( 'k1', 'v1', 'k2', 'v2', 'k3', 'v3' ) as $key => $val}
{$key} => {$val}

{if eq( $val, 'v2' )}
{break}
{/if}
{/foreach}
=====================================
{* test delimiter/continue/skip/sequence *}
{def $array=array( 'a', 'b', 'c', 'd', 'e', 'f' )}
{def $seq=array( 'dark', 'light' )}

{foreach $array as $i sequence $seq as $color}
{delimiter} :: {/delimiter}
{if eq( $i, 'c' )}
{skip}
{elseif eq( $i, 'e' )}
{continue}
{/if}
{$i}
{/foreach}

=====================================
{* test delimiter/continue/skip/sequence, delimiter at bottom *}
{foreach $array as $i sequence $seq as $color}
{if eq( $i, 'c' )}
{skip}
{elseif eq( $i, 'e' )}
{continue}
{/if}
{$i}
{delimiter} :: {/delimiter}
{/foreach}

=====================================
{* test max/offset *}
{foreach $array as $i max 3 offset 1}
{$i}

{/foreach}
=====================================
{* test max/offset/reverse *}
{foreach $array as $i max 3 offset 1 reverse}
{$i}

{/foreach}

{* this should produce no errors/warnings *}
{foreach array() as $i}
{/foreach}
