<form method="post" action={"collaboration/action/"|ezurl}>

{let content_version=fetch("content","version",hash("object_id",$collab_item.content.content_object_id,"version_id",$collab_item.content.content_object_version))
     participant_list=fetch("collaboration","participant_list",hash("item_id",$collab_item.id))}

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
  <td rowspan="2">

<h1>Approval</h1>

{section show=$collab_item.is_creator}
<p>The content object {content_version_view_gui view=text_linked content_version=$content_version} awaits approval before it can be published.</p>
<p>If you wish you may send a message to the person approving it?</p>
{section-else}
<p>The content object {content_version_view_gui view=text_linked content_version=$content_version} needs your approval before it can be published.</p>
<p>Do you approve of the content object being published?</p>
{/section}

<input type="hidden" name="CollaborationActionCustom" value="custom" />
<input type="hidden" name="CollaborationTypeIdentifier" value="ezapprove" />

<input type="hidden" name="CollaborationItemID" value="{$collab_item.id}" />

<br/>

<label>Comments</label><div class="break"/>
<textarea class="box" name="ApproveComment" cols="50" rows="6"></textarea>

<div class="buttonblock">
<input type="submit" name="CommentButton" value="Add Comment" />

&nbsp;

{section show=$collab_item.is_creator|not}
<input type="submit" name="AcceptButton" value="Approve" />
<input type="submit" name="DenyButton" value="Deny" />
{/section}
</div>

  </td>
  <td rowspan="2" valign="top">

   <h1>Participants</h1>
   <table cellspacing="0" cellpadding="0" border="0">
   {section name=Participant loop=$participant_list sequence=array(bglight,bgdark)}
   <tr>
     <td>
     {section show=eq($:item.participant_type,1)}
       {content_view_gui view=text_linked content_object=$:item.participant.contentobject}
     {section-else}
       {content_view_gui view=text_linked content_object=$:item.participant}
     {/section}
     </td>
   </tr>
   {/section}
   </table>

  </td>
  <th>Content object class - {$content_version.contentobject.content_class.name}</th>
</tr>
  <td valign="top">

{content_version_view_gui view=full content_version=$content_version}

  </td>
</tr>
</table>

{/let}

</form>
