<form method="post" action={"collaboration/action/"|ezurl}>

{let message_limit=2
     message_offset=0
     content_version=fetch("content","version",hash("object_id",$collab_item.content.content_object_id,"version_id",$collab_item.content.content_object_version))
     current_participant=fetch("collaboration","participant",hash("item_id",$collab_item.id))
     participant_list=fetch("collaboration","participant_map",hash("item_id",$collab_item.id))
     message_list=fetch("collaboration","message_list",hash("item_id",$collab_item.id,"limit",$message_limit,"offset",$message_offset))}

{set-block variable=contentobject_link}
{content_version_view_gui view=text_linked content_version=$content_version}
{/set-block}

<table width="100%" cellspacing="4" cellpadding="4" border="0">
<tr>
  <td rowspan="2" align="top">

<h1>{"Approval"|i18n('design/standard/collaboration/approval')}</h1>

{switch match=$collab_item.data_int3}
{case match=0}

{section show=$collab_item.is_creator}
<p>{"The content object %1 awaits approval before it can be published."|i18n('design/standard/collaboration/approval',,array($contentobject_link))}</p>
<p>{"If you wish you may send a message to the person approving it?"|i18n('design/standard/collaboration/approval')}</p>
{section-else}
<p>{"The content object %1 needs your approval before it can be published."|i18n('design/standard/collaboration/approval',,array($contentobject_link))}</p>
<p>{"Do you approve of the content object being published?"|i18n('design/standard/collaboration/approval')}</p>
{/section}

{/case}
{case match=1}
  <p>{"The content object %1 was approved and will be published once the publishing workflow continues."|i18n('design/standard/collaboration/approval',,array($contentobject_link))}</p>
{/case}
{case match=2}
  <p>{"The content object %1 was not approved and will be archived."|i18n('design/standard/collaboration/approval',,array($contentobject_link))}</p>
{/case}
{case match=3}
  {section show=$collab_item.is_creator}
    <p>{"The content object %1 was deferred and is available as a draft."|i18n('design/standard/collaboration/approval',,array($contentobject_link))}</p>
    <p>{"You must reedit the draft and publish it again for the approval to continue."|i18n('design/standard/collaboration/approval')}</p>
    <p>{"If the approver finds the new changes satisfying the object will be accepted."|i18n('design/standard/collaboration/approval')}</p>
    <p><a href={concat("content/edit/",$content_version.contentobject_id,"/",$content_version.version)|ezurl}>{"Edit"|i18n('design/standard/collaboration/approval')}</a></p>
  {section-else}
    <p>{"The content object %1 was deferred and will be available as a draft."|i18n('design/standard/collaboration/approval',,array($contentobject_link))}</p>
    <p>{"The author must reedit the draft and publish it again for the approval to continue."|i18n('design/standard/collaboration/approval')}</p>
  {/section}
{/case}
{case/}
{/switch}

<input type="hidden" name="CollaborationActionCustom" value="custom" />
<input type="hidden" name="CollaborationTypeIdentifier" value="ezapprove" />

<input type="hidden" name="CollaborationItemID" value="{$collab_item.id}" />

<br/>

{section show=eq($collab_item.data_int3,0)}
<label>{"Comment"|i18n('design/standard/collaboration/approval')}</label><div class="break"/>
<textarea class="box" name="Collaboration_ApproveComment" cols="50" rows="5"></textarea>

<div class="buttonblock">
<input type="submit" name="CollaborationAction_Comment" value="{'Add Comment'|i18n('design/standard/collaboration/approval')}" />

&nbsp;

{section show=$collab_item.is_creator|not}
<input type="submit" name="CollaborationAction_Accept" value="{'Approve'|i18n('design/standard/collaboration/approval')}" />
<input type="submit" name="CollaborationAction_Deny" value="{'Deny'|i18n('design/standard/collaboration/approval')}" />
<input type="submit" name="CollaborationAction_Defer" value="{'Defer'|i18n('design/standard/collaboration/approval')}" />
{/section}
</div>
{/section}

  </td>
  <td rowspan="2" valign="top">

   <h1>{"Participants"|i18n('design/standard/collaboration/approval')}</h1>
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
  <th>{"Content object class - %1"|i18n('design/standard/collaboration/approval',,array($content_version.contentobject.content_class.name))}</th>
</tr>
  <td valign="top">

{content_version_view_gui view=full content_version=$content_version}

  </td>
</tr>
</table>

{section show=$message_list}

  <h1 id="messages">{"Messages"|i18n('design/standard/collaboration/approval')}</h1>
  <table width="100%" cellspacing="0" cellpadding="4" border="0">
  {section name=Message loop=$message_list sequence=array(bglight,bgdark)}

      {collaboration_simple_message_view view=element sequence=$:sequence is_read=$current_participant.last_read|gt($:item.modified) item_link=$:item collaboration_message=$:item.simple_message}

  {/section}
  </table>

{/section}

{/let}

</form>
