{sum( 1, 2, 3, 4, 5 )}


{let a=2    b=3 c=array( 1, 2, 3 )}

	a tab
    spaces

{section show=$a}
    {section var=i loop=$c}
    {run-once}i: {/run-once}
        {$i}
    {delimiter}
        /	
    {/delimiter}
    {/section}

{section-else}

    {$b}
    {set b=5}
    {$b}

{/section}


{ldelim}section{rdelim}

{switch match=$a}
{case match=1}
#1
{/case}
{case match=2}
#2
{/case}
{case match=3}
#3
{/case}
{case/}
{/switch}

{default d=42}
    {$d}
{/default}



{/let}

