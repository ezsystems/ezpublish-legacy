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

{cache-block expiry=10 keys=$data}
{sum(1,2,3)}
{/cache-block}

