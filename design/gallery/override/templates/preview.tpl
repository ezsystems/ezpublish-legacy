<form method="post" action={concat("content/versionview/",$object.id,"/",$object_version,"/",$language|not|choose(array($language,"/"),""))|ezurl}>

{node_view_gui view=full content_object=$object node_name=$object.name content_node=$assignment.temp_node node=$node is_preview}

<input type="hidden" name="ContentObjectID" value="{$object.id}" />
<input type="hidden" name="ContentObjectVersion" value="{$object_version}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$object_languagecode}" />
<input type="hidden" name="ContentObjectPlacementID" value="{$placement}" />

<div class="buttonblock">
{section show=and(eq($version.status,0),$is_creator,$object.can_edit)}
    <input class="button" type="submit" name="EditButton" value="Back" />
    <input class="defaultbutton" type="submit" name="PreviewPublishButton" value="Continue" />
{/section}
</div>

</form>
