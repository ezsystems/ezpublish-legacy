{* Folder - Admin preview *}
<div class="content-view-full">
    <div class="class-folder">

        <h1>{$node.data_map.name.content|wash()}</h1>

        {* Short description. *}
        {section show=$node.data_map.short_description.has_content}
            <div class="attribute-short">
                {attribute_view_gui attribute=$node.data_map.short_description}
            </div>
        {/section}

        {* Description *}
        {section show=$node.data_map.description.has_content}
            <div class="attribute-long">
                {attribute_view_gui attribute=$node.data_map.description}
            </div>
        {/section}

        {* Children. *}
        <div class="content-control">
            <label>{'Show children'|i18n( 'design/admin/preview/folder' )}:</label>
            {section show=$node.data_map.show_children.content}
                <p>{'Yes'|i18n( 'design/admin/preview/folder' )}</p>
                {section-else}
                <p>{'No'|i18n( 'design/admin/preview/folder' )}</p>
            {/section}
        </div>

    </div>
</div>
