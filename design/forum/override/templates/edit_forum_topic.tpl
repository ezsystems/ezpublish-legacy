<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>
<table class="layout" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Left part start -->
    <div class="maincontentheader">
    <h1>{"Edit %1 - %2"|i18n("design/standard/content/edit",,array($class.name|wash,$object.name|wash))}</h1>
    </div>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" /> 
    <br/>

    
    <h2>Subject</h2>
    {attribute_edit_gui attribute=$object.data_map.subject}
    <h2>Message</h2>
    {attribute_edit_gui attribute=$object.data_map.message}

    <h2>Notify me about updates</h2>
    {attribute_edit_gui attribute=$object.data_map.notify_me_about_updates}
   

{let user_group_array=$object.current.creator.parent_nodes
     show_sticky_group_array=ezini('StickyGroup','GroupID','forum.ini')
     show_sticky=false()}

{section loop=$user_group_array}
  {section show=$show_sticky_group_array|contains($:item)}
     {set show_sticky=true()}
  {/section}
{/section}

{section show=$show_sticky}
    <h2>Sticky</h2>
    {attribute_edit_gui attribute=$object.data_map.sticky}
{section-else}

{/section}

{/let}

    <div class="buttonblock">
    <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/standard/content/edit')}" />
    <input class="defaultbutton" type="submit" name="PublishButton" value="{'Post'|i18n('design/standard/content/edit')}" />
    </div>
    <!-- Left part end -->
    </td>
</tr>
</table>

</form>