{* Feedback form - Full view *}

<div class="content-view-full">
    <div class="class-feedback-form">

        <h1>{$node.name|wash()}</h1>

        {include name='Validation' uri='design:content/collectedinfo_validation.tpl' class='message-warning'}

        <div class="attribute-short">
            {attribute_view_gui attribute=$node.data_map.description}
        </div>
        <form method="post" action={"content/action"|ezurl}>
	        {foreach $node.data_map as $attribute}
		        {if $attribute.is_information_collector}
		            <h2>{$attribute.contentclass_attribute_name|wash()}{if $attribute.is_required} <span class="required">*</span>{/if}</h2>
		            <div class="attribute-{$attribute.contentclass_attribute_identifier}">
		                {attribute_view_gui attribute=$attribute}
		            </div>
	            {/if}
	        {/foreach}
	
	        <div class="content-action">
	            <input type="submit" class="defaultbutton" name="ActionCollectInformation" value="{"Send form"|i18n("design/base")}" />
	            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
	            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
	            <input type="hidden" name="ViewMode" value="full" />
	        </div>
        </form>

    </div>
</div>
