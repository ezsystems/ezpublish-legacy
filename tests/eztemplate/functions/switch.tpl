{let myvar='derick'}

{switch name=Sw1 match=$myvar}
{case match='derick'}
This one does match.
match : {$:match}
{/case}

{case match='amos'}
This one does not match.
{/case}

{case}
Not this one either.
{/case}
{/switch}
{/let}

=========================================================

{let myvar='amos'}

{switch match=$myvar}
{case match='derick'}
This one doesn't match.
{/case}

{case match='amos'}
This one does match.
{$match}
{/case}

{case}
This one doesn't match.
{/case}
{/switch}
{/let}

=========================================================

{let myvar='bård'}

{switch name=Sw2 var=foobar match=$myvar}
{case match='derick'}
This one matches.
match : {$:match}
{/case}

{case match='amos'}
This one does not match.
{/case}

{case}
Not this one either.
We were trying to find {$:foobar}
{/case}
{/switch}
{/let}

=========================================================

{let my_array=array(2, 3, 4)
     myvar=2}
{switch name=Sw3 match=$myvar}

{case in=$my_array}
Match : {$:match}
{/case}

{case}
No Match for {$:match}
{/case}

{/switch}
{/let}