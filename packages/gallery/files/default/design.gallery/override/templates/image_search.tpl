{* Image template *}
{default href=$node.url_alias|ezurl}

<div class="image_search">
    <h2><a href={$node.url_alias|ezurl}>{$node.name|wash}</a></h2>

    {attribute_view_gui attribute=$node.object.data_map.image href=$href image_class=small_h}

    <p class="caption">{attribute_view_gui attribute=$node.object.data_map.caption}</p>
</div>
{/default}
