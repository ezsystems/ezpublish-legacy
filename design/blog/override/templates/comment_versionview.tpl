<div id="comment">
<h2>{$node.name|wash()}</h2>

<p>
  {$node.object.data_map.email.content}
</p>

<p>
  {$node.object.data_map.url.content|autolink()}
</p>

<p>
  {$node.object.data_map.comment.content|wash()|nl2br()|wordtoimage()|autolink()}
</p>

<form method="post" action={concat("content/versionview/",$object.id,"/",$object_version,"/",$language|not|choose(array($language,"/"),""))|ezurl}>

<div class="block">
    <div class="break">
    </div>
</div>

<input type="hidden" name="ContentObjectID" value="{$object.id}" />
<input type="hidden" name="ContentObjectVersion" value="{$object_version}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$object_languagecode}" />
<input type="hidden" name="ContentObjectPlacementID" value="{$placement}" />

<div class="buttonblock">
{section show=and(eq($version.status,0),$is_creator,$object.can_edit)}
<input class="button" type="submit" name="PreviewPublishButton" value="{'Publish'|i18n('design/standard/content/view')}" />
<input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/content/view')}" />
{/section}

</div>
</form> 
</div>