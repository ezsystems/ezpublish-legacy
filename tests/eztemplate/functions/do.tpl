{* usual case *}
{def $i=1}
{do}
i={$i}

{if eq( $i, 5 )}
{break}
{/if}
{set $i=inc( $i )}
{/do while 1}
================================================
{* test delimiter/skip/sequence *}
{set $i=1}
{do}
{delimiter}

==

{/delimiter}
{if gt( $i, 10 )}
{break}
{elseif mod( $i, 2)|not()}
{set $i=inc( $i )}
{skip}
{/if}
{$type}{$i}
{set $i=inc( $i )}
{/do while 1 sequence array( '+', '-') as $type}
================================================
{* test delimiter/continue/sequence *}
{set $i=1}
{do}{delimiter} :: {/delimiter}
{if mod( $i, 2)}<skip>{set $i=inc( $i )}{continue}{/if}
{$type}{$i}
{set $i=inc( $i )}
{/do while le( $i, 10 ) sequence array( '+', '-') as $type}
{* end *}
