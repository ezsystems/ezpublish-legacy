{* Link - Line view *}

<div class="content-view-line">
    <div class="class-link">

    <h2>{$node.name}</h2>

    {section show=$node.object.data_map.description.content.is_empty|not}
        <div class="attribute-long">
            {attribute_view_gui attribute=$node.object.data_map.description}
        </div>
    {/section}

    {section show=ne( $node.object.data_map.link.content, '' )}
        <div class="attribute-link">
            <p><a href="{$node.object.data_map.link.content}">{$node.object.data_map.link.data_text}</a></p>
        </div>
    {/section}

    </div>
</div>