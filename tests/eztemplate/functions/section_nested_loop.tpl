{$top.name}

{section var=node loop=$top.children}
================
    {$node.name}
    {section var=obj loop=$node.children}

----------------
        {$obj.name}

{section var=el loop=$obj.object.list}
{$el}{delimiter}:{/delimiter}
{/section}

----------------
    {/section}

================
{/section}
