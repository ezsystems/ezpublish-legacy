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

{let arr=hash( a, 5, c, false() )}

A: {section show=$arr[a]}A is true{section-else}A is not true{/section}

B: {section show=$arr[b]}B is true{section-else}B is not true{/section}

C: {section show=$arr[c]}C is true{section-else}C is not true{/section}

{/let}
