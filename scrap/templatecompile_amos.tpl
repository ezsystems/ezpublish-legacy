{*{let attributes=$object.attributes}

{$attributes[0].name}

{attribute_edit_gui attribute=$attributes[0]}

{$attributes[1].name}

{attribute_edit_gui attribute=$attributes[1] title="abc"}

{/let}*}

{*{include name=NS title="abc" attribute=$object.attributes[1] uri="scrap/design/standard/templates/content/datatype/edit/eztext.tpl"}*}

{include uri="design:page_mainarea.tpl"}
