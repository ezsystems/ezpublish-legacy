{* Custom edit template for articles in user view *}

<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>

<div id="article">
    <div class="edit">

        <div class="object_header">
            <h1>{"Edit %1 - %2"|i18n("design/standard/content/edit",,array($class.name|wash,$object.name|wash))}</h1>
        </div>
	<input type="hidden" name="MainNodeID" value="{$main_node_id}" /> 

        {include uri="design:content/edit_validation.tpl"}

{*        {include uri="design:content/edit_dropdown_placement.tpl"
                 placement=hash( list, array( hash( node_list, hash( 2, "Root", 4, "News" ) ),
                                              hash( node_list, hash( 5, "Users" ) ) ) )}*}
{*        {include uri="design:content/edit_placement.tpl"}*}

{*        {include uri="design:content/edit_right_menu.tpl"}*}
        {include uri="design:content/edit_info.tpl"}


        {include uri="design:content/edit_attribute.tpl"}

        <div class="controls">
            <input class="defaultbutton" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('design/standard/content/edit')}" />
            <input class="button" type="submit" name="StoreButton" value="{'Store draft'|i18n('design/standard/content/edit')}" />
            <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/standard/content/edit')}" />
        </div>

    </div>
</div>

</form>
