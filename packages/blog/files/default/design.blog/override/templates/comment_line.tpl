<h4>{$node.object.published|l10n(datetime)} | by {$node.object.data_map.name.content|wash()}</h4>
<p>
  {$node.object.data_map.comment.content|wash()|nl2br()|wordtoimage()|autolink()}
</p>
{section show=$node.object.can_remove}
  {let object=$node.object
       version=$node.contentobject_version
       language=$object.default_language}
    <form enctype="multipart/form-data" method="post" action={concat("/content/action")|ezurl}>
{*      <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />*}
      <input type="hidden" name="DeleteIDArray[]" value="{$node.node_id}" />
{*      <input type="hidden" name="ContentObjectID" value="{$object.id}" /> *}
      <input class="button" type="submit" name="RemoveButton" value="{'Remove'|i18n('design/standard/content/edit')}" />
    </form>
  {/let}
{/section}
