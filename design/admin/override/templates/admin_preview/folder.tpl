{* Folder - Admin preview *}
<div class="content-view-full">
    <div class="class-folder">
        <h1>{$node.object.data_map.name.content|wash()}</h1>

        {section show=$node.object.data_map.short_description.has_content}
            <div class="attribute-short">
                {attribute_view_gui attribute=$node.object.data_map.short_description}
            </div>
        {/section}

        {section show=$node.object.data_map.description.has_content}
            <div class="attribute-long">
                {attribute_view_gui attribute=$node.object.data_map.description}
            </div>
        {/section}

        {* Children. *}
        <div class="content-control">
            <label>Show chilren:</label>
            {section show=$node.object.data_map.show_children.has_content}
                <p>Yes</p>
                {section-else}
                <p>No</p>
            {/section}
        </div>

    </div>
</div>
