{default attribute_parameters=array()}
{section show=$object.main_node_id}
    <a href={$object.main_node.url_alias|ezurl}>{$object.name|wash}</a>
{section-else}
    {$object.name|wash}
{/section}
{/default}