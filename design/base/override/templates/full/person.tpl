{* Person - Full view *}

<div class="content-view-full">
    <div class="class-person">
    <form method="post" action={"content/action"|ezurl}>
    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
    <input type="hidden" name="ViewMode" value="full" />

    <h1>{$node.name|wash} ( {attribute_view_gui attribute=$node.object.data_map.job_title} )</h1>

    {section show=$node.object.can_edit}
        <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
    {/section}

    {section show=$node.object.data_map.picture.content}
        <div class="attribute-image">
            {attribute_view_gui attribute=$node.object.data_map.picture.content.data_map.image alignment=right}
        </div>
    {/section}

    <div class="attribute-matrix">
    <h2>{"Contact information"|i18n("design/intranet/layout")}</h2>
        {attribute_view_gui attribute=$node.object.data_map.person_numbers}
    </div>

    {section show=$node.object.data_map.comment.content.is_empty|not}
    <h2>Comments</h2>
        <div class="attribute-long">
            {attribute_view_gui attribute=$node.object.data_map.comment}
        </div>
    {/section}

    </form>
    </div>
</div>
