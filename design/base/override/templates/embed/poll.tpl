{* Poll - Embed view *}
<div class="content-view-embed">
    <div class="class-poll">
        <h2>{$object.name|wash}</h2>

       <div class="content-body">
        <form method="post" action={"content/action"|ezurl}>
        <input type="hidden" name="ContentNodeID" value="{$object.main_node_id}" />
        <input type="hidden" name="ContentObjectID" value="{$object.id}" />
        <input type="hidden" name="ViewMode" value="full" />

        {let attribute=$object.data_map.question
             option_id=cond( is_set( $#collection_attributes[$attribute.id]), $#collection_attributes[$attribute.id].data_int,false() )}

        <h3>{$attribute.content.name}</h3>

        {section name=OptionList loop=$attribute.content.option_list sequence=array(bglight,bgdark)}
            <input type="radio" name="ContentObjectAttribute_data_option_value_{$attribute.id}" value="{$OptionList:item.id}"
           {if $OptionList:item.id|eq($option_id)}checked="checked"{/if}
           />{$OptionList:item.value}<br />
        {/section}

        {/let}
        <input class="button" type="submit" name="ActionCollectInformation" value={"Vote"|i18n("design/base")} />

        </form>
        </div>
    </div>
</div>
