{let attributes=$node.object.attributes}
Name: {$node.name}

Edit gui:

{section var=attribute loop=$attributes}
{$attribute.name}:

{attribute_edit_gui attribute=$attribute}
{delimiter}


{/delimiter}
{/section}

------------------------------

View gui:

{section var=attribute loop=$attributes}
*{$attribute.name}*

{attribute_view_gui attribute=$attribute title="abc"}
{delimiter}
-------------------------------

{/delimiter}
{/section}

{/let}

Children:

{section var=child loop=$node.children}
{delimiter}================================

{/delimiter}
{node_view_gui view=line content_node=$child}

{/section}
