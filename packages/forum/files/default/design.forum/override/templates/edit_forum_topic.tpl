<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>
    <h1>{"Edit %1 - %2"|i18n("design/standard/content/edit",,array($class.name|wash,$object.name|wash))}</h1>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" /> 

    <div class="block">
    <label>{"Subject"|i18n("design/forum/layout")}</label>
    {attribute_edit_gui attribute=$object.data_map.subject}
    </div>

    <div class="block">
    <label>{"Message"|i18n("design/forum/layout")}</label>
    {attribute_edit_gui attribute=$object.data_map.message}
    </div>
    
    <div class="block">
    <label>{attribute_edit_gui attribute=$object.data_map.notify_me_about_updates} Notify me about updates</label> 

{let user_group_array=$object.current.creator.parent_nodes
     show_sticky_group_array=ezini('StickyGroup','GroupID','forum.ini')
     show_sticky=false()}

{section loop=$user_group_array}
  {section show=$show_sticky_group_array|contains($:item)}
     {set show_sticky=true()}
  {/section}
{/section}

{section show=$show_sticky}
    <label>{attribute_edit_gui attribute=$object.data_map.sticky} Sticky</label>
{section-else}

{/section}

{/let}
    </div>    

<div class="buttonblock">
<input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('design/standard/content/edit')}" />
<input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/standard/content/edit')}" />
<input class="defaultbutton" type="submit" name="PublishButton" value="{'Post'|i18n('design/standard/content/edit')}" />
</div>

</form>
