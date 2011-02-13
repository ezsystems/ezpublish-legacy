{* Person - Full view *}

<div class="content-view-full">
    <div class="class-person">
    <form method="post" action={"content/action"|ezurl}>
    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
    <input type="hidden" name="ViewMode" value="full" />

    <h1>{$node.name|wash} ( {attribute_view_gui attribute=$node.data_map.job_title} )</h1>

    {if $node.object.can_edit}
        <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/base')}" />
    {/if}

    {if $node.data_map.picture.content}
        <div class="attribute-image">
            {attribute_view_gui attribute=$node.data_map.picture.content.data_map.image alignment=right}
        </div>
    {/if}

    <div class="attribute-matrix">
    <h2>{"Contact information"|i18n("design/base")}</h2>
        {attribute_view_gui attribute=$node.data_map.contact_information}
    </div>

    <h2>{'Comments'|i18n( 'design/base' )}</h2>
    <div class="attribute-long">
        {attribute_view_gui attribute=$node.data_map.comment}
    </div>

    </form>
    </div>
</div>
