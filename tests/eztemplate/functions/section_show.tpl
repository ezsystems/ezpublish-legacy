{let a=true() b=false() c=array() d=array( 1 )}
{section show=true()}
This should be shown
{/section}

{section show=false()}
This should not be shown
{/section}


{section show=$a}
a is true
{section-else}
a is not true
{/section}


{section show=$b}
b is true
{section-else}
b is not true
{/section}


{section show=$c}
c is true
{section-else}
c is not true
{/section}


{section show=$d}
d is true
{section-else}
d is not true
{/section}

{/let}
