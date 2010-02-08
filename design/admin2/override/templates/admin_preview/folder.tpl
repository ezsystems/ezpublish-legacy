{* Folder - Admin preview *}
<div class="content-view-full">
    <div class="class-folder">

        <h1>{$node.data_map.name.content|wash()}</h1>

        {* Short description. *}
        {if $node.data_map.short_description.has_content}
            <div class="attribute-short">
                {attribute_view_gui attribute=$node.data_map.short_description}
            </div>
        {/if}

        {* Description *}
        {if $node.data_map.description.has_content}
            <div class="attribute-long">
                {attribute_view_gui attribute=$node.data_map.description}
            </div>
        {/if}

        {* Children. *}
        <div class="content-control">
            <h6>{'Show children'|i18n( 'design/admin/preview/folder' )}:</h6>
            {if $node.data_map.show_children.content}
                <p>{'Yes'|i18n( 'design/admin/preview/folder' )}</p>
                {else}
                <p>{'No'|i18n( 'design/admin/preview/folder' )}</p>
            {/if}
        </div>

    </div>
</div>
