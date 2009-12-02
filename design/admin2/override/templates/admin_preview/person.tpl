{* Person - Admin preview *}
<div class="content-view-full">
    <div class="class-person">
    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
    <input type="hidden" name="ViewMode" value="full" />

    <h1>{$node.name|wash} ( {attribute_view_gui attribute=$node.data_map.job_title} )</h1>

    {* Picture. *}
    {if $node.data_map.picture.content}
        <div class="attribute-image">
            {attribute_view_gui attribute=$node.data_map.picture.content.data_map.image alignment=right}
        </div>
    {/if}

    {* Contact information. *}
    <div class="attribute-matrix">
    <h2>{'Contact information'|i18n( 'design/admin/preview/person' )}</h2>
        {attribute_view_gui attribute=$node.data_map.contact_information}
    </div>

    <h2>{'Comments'|i18n( 'design/admin/preview/person' )}</h2>
    <div class="attribute-long">
        {attribute_view_gui attribute=$node.data_map.comment}
    </div>

    </div>
</div>
