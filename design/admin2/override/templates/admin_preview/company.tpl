{* Company - Admin preview *}
<div class="content-view-full">
    <div class="class-company">

    <h1>{$node.name|wash} ( {attribute_view_gui attribute=$node.data_map.company_number} )</h1>

    {if $node.data_map.logo.content}
        <div class="attribute-image">
            {attribute_view_gui attribute=$node.data_map.logo.content.data_map.image alignment=right}
        </div>
    {/if}

    <div class="attribute-matrix">
        <h2>{'Contact information'|i18n( 'design/admin/preview/company' )}</h2>
        {attribute_view_gui attribute=$node.data_map.contact_information}
    </div>

    <div class="attribute-matrix">
        <h2>{'Address'|i18n( 'design/admin/preview/company' )}</h2>
        {attribute_view_gui attribute=$node.data_map.company_address}
    </div>

    <div class="attribute-long">
        <h2>{'Additional information'|i18n( 'design/admin/preview/company' )}</h2>
        {attribute_view_gui attribute=$node.data_map.additional_information}
    </div>

    <div class="attribute-objectrelationlist">

        <h2>{'Contacts'|i18n( 'design/admin/preview/company' )}</h2>

        <table class="list" cellspacing="0">
        {section name=Relation loop=$node.data_map.contacts.content.relation_list}
            <tr>
                <td>
                {node_view_gui view=line content_node=fetch( content,node,hash(node_id,$:item.node_id ) )}
                </td>
            </tr>
        {/section}
        </table>

    </div>

    </div>
</div>
