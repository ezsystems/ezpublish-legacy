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

{let arr=array( 1, 2, 'b', 4, 5, 6, 7, 8 ) show=true len=6 mod=2}

{section show=$show}
Yes
{section-else}
Nope
{/section}


{section var=i loop=$arr offset=2 max=$len reverse last-value}
{section-exclude match=true()}
{section-include match=eq($i,2)}
{section-include match=ge($i,7)}
Value: {$i}
{section show=eq($i,2)}
 [Blah({$i})]
{/section}

last({$i.last.key})

{delimiter modulo=$mod} / {/delimiter}

{/section}

{/let}
