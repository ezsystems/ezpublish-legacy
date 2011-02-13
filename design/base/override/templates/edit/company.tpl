{* Custom edit template for company *}

<form enctype="multipart/form-data" method="post" action={concat( "/content/edit/", $object.id, "/", $edit_version, "/", $edit_language|not|choose( concat( $edit_language, "/" ), '' ) )|ezurl}>

<div class="log">
    <div class="edit">

        <div class="object_header">
            <h1>{"Edit %1 - %2"|i18n("design/base",,array($class.name|wash,$object.name|wash))}</h1>
        </div>
	    <input type="hidden" name="MainNodeID" value="{$main_node_id}" />

        {include uri="design:content/edit_validation.tpl"}

        {include uri="design:content/edit_attribute.tpl"}

        <div class="controls">
            <input class="defaultbutton" type="submit" name="PublishButton" value="{'Publish'|i18n('design/base')}" />
            <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/base')}" />
            <input type="hidden" name="DiscardConfirm" value="0" />
        </div>

    </div>
</div>
</form>