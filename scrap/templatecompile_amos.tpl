{*{let attributes=$object.attributes}

{$attributes[0].name}

{attribute_edit_gui attribute=$attributes[0]}

{$attributes[1].name}

{attribute_edit_gui attribute=$attributes[1] title="abc"}

{/let}*}

{*{include name=NS title="abc" attribute=$object.attributes[1] uri="scrap/design/standard/templates/content/datatype/edit/eztext.tpl"}*}

{*{switch name=NS match=2}
{case match=1}
Match: {$:match}
{/case}
{case match=2}
Match: {$:match}
{/case}
{/switch}*}

{*{let name=BS arr=array( 1, 2, 'b', 4, 5, 6, 7, 8 ) times=8 str="12b45678" show=true len=6 mod=2}*}

{*{section show=$:show}
Yes
{section-else}
Nope
{/section}*}


{*{let myvar='bård'}

{append-block name=NS1 scope=root variable=blah}
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
{/set-block}

{/let}*}


{let arr=array(1,2)}
{$arr|array_prepend(3)|implode(',')}
{$arr|array_append(3)|implode(',')}
{$arr|array_merge(3,4)|implode(',')}
{/let}




{*{section name=NS var=i loop=$:times offset=2 max=$:len reverse last-value sequence=array(odd,even)}
{section-include match=eq($:i,2)}
{section-include match=ge($:i,7)}
Value<{$:i.sequence}>: {$BS:NS:i}
{section show=eq($:i,2)}
 [Blah({$:i})]
{/section}

last({$:i.last})
last.last({$:i.last.last})
last.last.last({$:i.last.last.last})

{delimiter modulo=$:mod} / {/delimiter}

{section-else}
What if?

{/section}*}


{*{section name=NS loop=$:times offset=2 max=$:len reverse last-value sequence=array(odd,even)}
{section-include match=eq($:item,2)}
{section-include match=ge($:item,7)}
Value<{$:sequence}>: {$BS:NS:item}
{section show=eq($:item,2)}
 [Blah({$:item})]
{/section}

last({$:item.last})
last.last({$:item.last.last})
last.last.last({$:item.last.last.last})

{delimiter modulo=$:mod} / {/delimiter}

{section-else}
What if?

{/section}*}


{*{/let}*}
