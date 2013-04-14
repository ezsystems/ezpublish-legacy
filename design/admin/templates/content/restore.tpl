<form action={concat( 'content/restore/', $object.id )|ezurl} method="post" name="ObjectRestore">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Object retrieval'|i18n( 'design/admin/content/restore' )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<p>{'Specify the location where you want to restore <%name>.'|i18n( 'design/admin/node/removeobject',, hash( '%name', $object.name ) )|wash}</p>
</div>

<div class="block">
{if $location}
<label title="{'The object will be restored at its original location.'|i18n( 'design/admin/content/restore' )|wash}"><input type="radio" name="RestoreType" value="1" checked="checked" title="{'The object will be restored at its original location.'|i18n( 'design/admin/content/restore' )|wash}" />&nbsp;{'Restore at original location (below <%nodeName>).'|i18n( 'design/admin/content/restore',, hash( '%nodeName', $location.parent_node_obj.name ) )|wash}</label>
<label title="{'The system will prompt you to specify a location by browsing the tree.'|i18n( 'design/admin/content/restore' )|wash}"><input type="radio" name="RestoreType" value="2" title="{'The system will prompt you to specify a location by browsing the tree.'|i18n( 'design/admin/content/restore' )|wash}" />&nbsp;{'Select a location.'|i18n( 'design/admin/content/restore' )}</label>
{else}
<label><input type="radio" disabled="disabled" name="RestoreType" value="1" />&nbsp;{'Restore at original location (unavailable).'|i18n( 'design/admin/content/restore' )}</label>
<label title="{'The system will prompt you to specify a location by browsing the tree.'|i18n( 'design/admin/content/restore' )|wash}"><input type="radio" name="RestoreType" value="2" checked="checked" title="{'The system will prompt you to specify a location by browsing the tree.'|i18n( 'design/admin/content/restore' )|wash}" />&nbsp;{'Select a location.'|i18n( 'design/admin/content/restore' )}</label>
{/if}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<input class="defaultbutton" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/content/restore' )}" title="{'Continue restoring <%name>.'|i18n( 'design/admin/content/restore',, hash( '%name', $object.name ) )|wash}" />
<input type="submit" class="button" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/content/restore' )}" title="{'Do not restore <%name> and return to trash.'|i18n( 'design/admin/content/restore',, hash( '%name', $object.name ) )|wash}" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
