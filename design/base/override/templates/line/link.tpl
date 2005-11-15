{* Link - Line view *}

<div class="content-view-line">
    <div class="class-link">

    <h2>{$node.name|wash}</h2>

    {section show=$node.data_map.description.has_content}
        <div class="attribute-long">
            {attribute_view_gui attribute=$node.data_map.description}
        </div>
    {/section}

    {section show=$node.data_map.location.has_content}
        <div class="attribute-link">
            <p><a href="{$node.data_map.location.content}">{section show=$node.data_map.location.data_text|count|gt( 0 )}{$node.data_map.location.data_text|wash}{section-else}{$node.data_map.location.content|wash}{/section}</a></p>
        </div>
    {/section}

    </div>
</div>