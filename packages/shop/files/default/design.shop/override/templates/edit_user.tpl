<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>
<input type="hidden" name="RedirectURI" value="/content/view/full/2" />
<table class="layout" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <div class="maincontentheader">
    <h1>{"Edit %1 - %2"|i18n("design/standard/content/edit",,array($class.name|wash,$object.name|wash))}</h1>
    </div>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" /> 
    <br/>

    {include uri="design:content/edit_attribute.tpl"}
    <br/>
    
    <div class="buttonblock">
    <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/standard/content/edit')}" />
    <input class="defaultbutton" type="submit" name="PublishButton" value="{'Store'|i18n('design/standard/content/edit')}" />
    </div>
    </td>
</tr>
</table>
</form>