<form method="post" action={"collaboration/action/"|ezurl}>

{let message_limit=2
     message_offset=0
     content_version=fetch("content","version",hash("object_id",$collab_item.content.content_object_id,"version_id",$collab_item.content.content_object_version))
     current_participant=fetch("collaboration","participant",hash("item_id",$collab_item.id))
     participant_list=fetch("collaboration","participant_map",hash("item_id",$collab_item.id))
     message_list=fetch("collaboration","message_list",hash("item_id",$collab_item.id,"limit",$message_limit,"offset",$message_offset))}

<table width="100%" cellspacing="4" cellpadding="4" border="0">
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

<label>Comment</label><div class="break"/>
<textarea class="box" name="Collaboration_ApproveComment" cols="50" rows="5"></textarea>

<div class="buttonblock">
<input type="submit" name="CollaborationAction_Comment" value="Add Comment" />

&nbsp;

{section show=$collab_item.is_creator|not}
<input type="submit" name="CollaborationAction_Accept" value="Approve" />
<input type="submit" name="CollaborationAction_Deny" value="Deny" />
{/section}
</div>

  </td>
  <td rowspan="2" valign="top">

   <h1>Participants</h1>
   {section name=Role loop=$participant_list sequence=array(bglight,bgdark)}
   <label>{$:item.name}</label>
   <table cellspacing="0" cellpadding="0" border="0">
   {section name=Participant loop=$:item.items sequence=array(bglight,bgdark)}
   <tr>
     <td>
     {collaboration_participation_view view=text_linked collaboration_participant=$:item}
     </td>
   </tr>
   {/section}
   </table>
   {delimiter}<br/>{/delimiter}
   {/section}

  </td>
  <th>Content object class - {$content_version.contentobject.content_class.name}</th>
</tr>
  <td valign="top">

{content_version_view_gui view=full content_version=$content_version}

  </td>
</tr>
</table>

{section show=$message_list}

  <h1><a name="messages" ></a>Messages</h1>
  <table width="100%" cellspacing="0" cellpadding="4" border="0">
  {section name=Message loop=$message_list sequence=array(bglight,bgdark)}

      {collaboration_simple_message_view view=element sequence=$:sequence is_read=$current_participant.last_read|gt($:item.modified) item_link=$:item collaboration_message=$:item.simple_message}

  {/section}
  </table>

{/section}

{/let}

</form>
