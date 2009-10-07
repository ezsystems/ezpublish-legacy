{* Link - Line view *}

<div class="content-view-line">
    <div class="class-link">

    <h2>{$node.name|wash}</h2>

    {if $node.data_map.description.has_content}
        <div class="attribute-long">
            {attribute_view_gui attribute=$node.data_map.description}
        </div>
    {/if}

    {if $node.data_map.location.has_content}
        <div class="attribute-link">
            <p><a href="{$node.data_map.location.content}">{if $node.data_map.location.data_text|count|gt( 0 )}{$node.data_map.location.data_text|wash}{else}{$node.data_map.location.content|wash}{/if}</a></p>
        </div>
    {/if}

    </div>
</div>