<div id="folder">

{section show=$node.object.can_edit}
<div class="editbutton">
   <input class="button" type="submit" name="EditButton" value="{'Edit'|i18n('design/standard/node/view')}" />
</div>
{/section}

<h1>{$node.name|wash}</h1>

<div class="object_content">
{attribute_view_gui attribute=$node.object.data_map.description}
</div>

{* Include latest news *}
{include uri="design:news_latest.tpl" 
         count="5" }

{* include latest forum posts *}
{include uri="design:forum_latest.tpl"
         count="5" }

{* Include forum list *}
{include uri="design:forum_list.tpl"
         node=fetch( content, node, hash( node_id, 111 ) ) }

</div>