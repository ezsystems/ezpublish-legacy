{let attributes=$object.attributes}
Name: {$object.name}

Edit gui:

{section var=attribute loop=$attributes}
{$attribute.name}:

{attribute_edit_gui attribute=$attribute}
{delimiter}


{/delimiter}
{/section}


View gui:

{section var=attribute loop=$attributes}
*{$attribute.name}*

{attribute_view_gui attribute=$attribute title="abc"}
{delimiter}
-------------------------------

{/delimiter}
{/section}

{/let}
