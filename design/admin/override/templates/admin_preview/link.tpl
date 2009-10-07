{* Link - Admin preview *}
<div class="content-view-full">
    <div class="class-link">

    <h1>{$node.name|wash}</h1>

    {* Description. *}
    {if $node.data_map.description.has_content}
        <div class="attribute-long">
            {attribute_view_gui attribute=$node.data_map.description}
        </div>
    {/if}

    {* URL/Link. *}
    {if $node.data_map.location.has_content}
        <div class="attribute-link">
            {if $node.data_map.location.data_text}
            <p><a href="{$node.data_map.location.content}">{$node.data_map.location.data_text}</a></p>
            {else}
            <p><a href="{$node.data_map.location.content}">{$node.data_map.location.content}</a></p>
            {/if}
        </div>
    {/if}

    </div>
</div>
