{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}
<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>

<script language=jscript src={"/extension/xmleditor/dhtml/ezeditor.js"|ezroot}></script>  
<link rel="stylesheet" type="text/css" href={"/extension/xmleditor/dhtml/toolbar.css"|ezroot}>
<table class="layout" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Left part start -->
    <div class="maincontentheader">
    <h1>{"Edit"|i18n("design/standard/content/edit")} {$class.name} - {$object.name}</h1>
    </div>

    <!-- Validation start -->
    {include uri="design:content/edit_validation.tpl"}
    <!-- Validation end -->

    <!-- Placement start -->
    {include uri="design:content/edit_placement.tpl"}
    <!-- Placement end -->

    <!-- Attribute edit start -->
    {include uri="design:content/edit_attribute.tpl"}
    <!-- Attribute edit start -->

    <div class="buttonblock">
    <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('design/standard/content/edit')}" />
    </div>
    <div class="buttonblock">
    <input class="button" type="submit" name="StoreButton" value="{'Store Draft'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/standard/content/edit')}" />
    </div>
    <!-- Left part end -->
    </td>
    <td width="120" align="right" valign="top" style="padding-left: 16px;">

    <!-- Right part start-->
    {include uri="design:content/edit_right_menu.tpl"}
    <!-- Right part end -->

    </td>
</tr>
</table>

</form>
