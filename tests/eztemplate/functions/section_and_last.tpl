{let arr=array( 1, 2, 'b', 4, 5, 6, 7, 8 ) times=8 str="12b45678" show=true len=6 mod=2}

{section var=out loop=$:times offset=2 max=$:len reverse last-value sequence=array( odd, even )}
Value<{$out.sequence}>: {$out}
{section show=eq( $out, 5 )}
 [Fiver({$out})]
{/section}

last({$out.last})
last.last({$out.last.last})
last.last.last({$out.last.last.last})

{delimiter modulo=$:mod} / {/delimiter}

{section-else}
What if?

{/section}


{section var=out loop=$:times offset=2 max=$:len last-value sequence=array( odd, even )}
Value<{$out.sequence}>: {$out}
{section show=eq( $out, 5 )}
 [Fiver({$out})]
{/section}

last({$out.last})
last.last({$out.last.last})
last.last.last({$out.last.last.last})

{delimiter modulo=$:mod} / {/delimiter}

{section-else}
What if?

{/section}

{/let}
