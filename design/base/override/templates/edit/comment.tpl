<form enctype="multipart/form-data" method="post" action={concat( "/content/edit/", $object.id, "/", $edit_version, "/", $edit_language|not|choose( concat( $edit_language, "/" ), '' ) )|ezurl}>

<div class="edit">
    <div class="class-article-comment">

    <h1>{"Edit %1 - %2"|i18n("design/base",,array($class.name|wash,$object.name|wash))}</h1>

    {include uri="design:content/edit_validation.tpl"}

    <br/>

    <div class="block">
       {let attribute=$object.data_map.subject}
       <label>{$attribute.contentclass_attribute.name}</label><div class="labelbreak"></div>
       <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
       <input class="box" type="text" size="70" name="ContentObjectAttribute_ezstring_data_text_{$attribute.id}" value="" />
       {/let}
    </div>

    {let user=fetch( user, current_user )
         attribute=$object.data_map.author}
    <div class="block">
        {if $user.is_logged_in}

        <input type="hidden" name="ContentObjectAttribute_ezstring_data_text_{$attribute.id}" value="{$user.contentobject.name|wash}" />
        {else}
            <label>{$attribute.contentclass_attribute.name}</label><div class="labelbreak"></div>
	    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
	    <input class="box" type="text" size="70" name="ContentObjectAttribute_ezstring_data_text_{$attribute.id}" value="" />
        {/if}
    </div>
    {/let}

    <div class="block">
       {let attribute=$object.data_map.message}
       <label>{$attribute.contentclass_attribute.name}</label><div class="labelbreak"></div>
       <input type="hidden" name="ContentObjectAttribute_id[]" value="{$attribute.id}" />
       <textarea class="box" cols="70" rows="10" name="ContentObjectAttribute_data_text_{$attribute.id}"></textarea>
       {/let}
    </div>

    <div class="buttonblock">
        <input class="defaultbutton" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('design/base')}" />
	    <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/base')}" />
        <input type="hidden" name="MainNodeID" value="{$main_node_id }" />
        <input type="hidden" name="DiscardConfirm" value="0" />
    </div>

    </div>
</div>
</form>
