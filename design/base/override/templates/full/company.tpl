{* Company - Full view *}

<div class="content-view-full">
    <div class="class-company">
    <form method="post" action={"content/action"|ezurl}>
    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
    <input type="hidden" name="ViewMode" value="full" />

    <h1><a href={$node.url_alias|ezurl}>{$node.name|wash}</a> ( {attribute_view_gui attribute=$node.object.data_map.company_number} )</h1>

    {section show=$node.object.can_edit}
        <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
    {/section}

    {section show=$node.object.data_map.logo.content}
        <div class="attribute-image">
            {attribute_view_gui attribute=$node.object.data_map.logo.content.data_map.image alignment=right}
        </div>
    {/section}

    <div class="attribute-matrix">
    <h2>{"Contact information"|i18n("design/intranet/layout")}</h2>
        {attribute_view_gui attribute=$node.object.data_map.company_numbers}
    </div>

    <div class="attribute-matrix">
    <h2>{"Address"|i18n("design/intranet/layout")}</h2>
        {attribute_view_gui attribute=$node.object.data_map.company_address}
    </div>

    <div class="attribute-long">
    <h2>{"Additional information"|i18n("design/intranet/layout")}</h2>
        {attribute_view_gui attribute=$node.object.data_map.additional_information}
    </div>

    <div class="attribute-objectrelationlist">
    <h2>{"Contacts"|i18n("design/intranet/layout")}</h2>
    <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
    {section name=Relation loop=$node.object.data_map.contacts.content.relation_list}
    <tr>
        <td>
            {node_view_gui view=line content_node=fetch(content,node,hash(node_id,$:item.node_id))}
        </td>
    </tr>
    {/section}
    </table>
    </div>

    </form>
    </div>
</div>