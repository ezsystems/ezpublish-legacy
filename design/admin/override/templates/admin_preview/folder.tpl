{* Folder - Admin preview *}
<div class="content-view-full">
    <div class="class-folder">
        <h1>{$node.object.data_map.name.content|wash()}</h1>

        {section show=$node.object.data_map.short_description.content.is_empty|not}
            <div class="attribute-short">
                {attribute_view_gui attribute=$node.object.data_map.short_description}
            </div>
        {/section}

        {section show=$node.object.data_map.description.content.is_empty|not}
            <div class="attribute-long">
                {attribute_view_gui attribute=$node.object.data_map.description}
            </div>
        {/section}
    </div>
</div>
