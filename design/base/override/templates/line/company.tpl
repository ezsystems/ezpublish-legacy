{* Company - Line view *}

<div class="content-view-line">
    <div class="class-company">

    <h2><a href={$node.url_alias|ezurl}>{$node.name|wash}</a> ( {attribute_view_gui attribute=$node.data_map.company_number} )</h2>

    {if $node.data_map.logo.content}
        <div class="attribute-image">
            {attribute_view_gui attribute=$node.data_map.logo.content.data_map.image alignment=right image_class=small href=$node.url_alias|ezurl}
        </div>
    {/if}

    <div class="attribute-matrix">
    <h3>{"Contact information"|i18n("design/base")}</h3>
        {attribute_view_gui attribute=$node.data_map.contact_information}
    </div>

    <div class="attribute-matrix">
    <h2>{"Address"|i18n("design/base")}</h2>
        {attribute_view_gui attribute=$node.data_map.company_address}
    </div>

    </div>

    <div class="break"></div>
</div>