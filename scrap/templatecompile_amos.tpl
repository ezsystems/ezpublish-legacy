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

{let b=10}

{cache-block expiry=10 keys=$data}
The magic number is: 
{let a=20}
{sum(1,$a,3)}
{/let}
{/cache-block}





{cache-block}
{sum(1,$b,3)}
{/cache-block}

{/let}
