{* if the condition is true, the children should be processed *}
{if 1}
ok1
{/if}

{* if the condition is true, children of {else} should not be processed *}
{if 1}
ok2
{else}
failed2
{/if}

{* if the condition is false, children of {else} should be processed *}
{if 0}
failed3
{else}
ok3
{/if}

{* if the condition is true, children of {else} and {elseif}'s should not be processed *}
{if 1}
ok4
{elseif 1}
failed4
{elseif 1}
failed4
{else}
failed4
{/if}

{* only children corresponding to the first met true condition should be processed *}
{if 0}
failed5
{elseif 1}
ok5
{elseif 1}
failed5
{else}
failed5
{/if}


{if 0}
failed6
{elseif 0}
failed6
{elseif 0}
failed6
{else}
ok6
{/if}

{* test nesting *}
{if 1}
{if 1}
ok7
{else}
failed7
{/if}
{else}
failed7}
{/if}

{* test more complex arguments *}
{if 1|not()}
failed8
{else}
ok8
{/if}

{* test non-constant arguments *}
{def $cond=1}

{if $cond}
ok9
{else}
failed9
{/if}
{undef $cond}
{* end *}
