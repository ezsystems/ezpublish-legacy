{* Company - List embed view *}
<div class="content-view-embed">
    <div class="class-company">
        <h2><a href={$object.main_node.url_alias|ezurl}>{$object.name|wash}</a> ( {attribute_view_gui attribute=$object.data_map.company_number} )</h2>

        <div class="content-body">

    {if $object.data_map.logo.content}
        <div class="attribute-image">
            {attribute_view_gui attribute=$object.data_map.logo.content.data_map.image alignment=right image_class=small href=$object.main_node.url_alias|ezurl}
        </div>
    {/if}

        <div class="attribute-matrix">
            <h3>{"Contact information"|i18n("design/base")}</h3>
            {attribute_view_gui attribute=$object.data_map.contact_information}
        </div>

        <div class="attribute-matrix">
            <h3>{"Address"|i18n("design/base")}</h3>
            {attribute_view_gui attribute=$node.data_map.company_address}
        </div>

        <div class="attribute-long">
            <h2>{"Additional information"|i18n("design/base")}</h2>
            {attribute_view_gui attribute=$node.data_map.additional_information}
        </div>

        </div>
    </div>
</div>