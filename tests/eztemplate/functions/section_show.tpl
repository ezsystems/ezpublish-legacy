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

A: {section show=first_set( $arr[a], false() )}A is true{section-else}A is not true{/section}

B: {section show=first_set( $arr[b], false() )}B is true{section-else}B is not true{/section}

C: {section show=first_set( $arr[c] )}C is true{section-else}C is not true{/section}

{/let}

Empty string:

{let article=hash( node_id2, '' )}
{section show=first_set( $article.node_id, false() )}
We have a non-empty string
{section-else}
Empty string
{/section}
{/let}

