{default content_version=$node.contentobject_version_object}
{let map=$content_version.data_map}

<div class="listitem">
    <h2>{attribute_view_gui attribute=$map.title}</h2>
    <div class="imageright">
        <a href={concat( "content/view/full/", $node.node_id )|ezurl}>
        {attribute_view_gui attribute=$map.photo border_size=1 hspace=10 alignment=right image_class=medium}
        </a>
    </div>
    <div class="intro">
        {attribute_view_gui attribute=$map.intro}
    </div>
    <p class="readmore"><a href={concat( "content/view/full/", $node.node_id )|ezurl}>Product information</a></p>
</div>

{/let}
{/default}