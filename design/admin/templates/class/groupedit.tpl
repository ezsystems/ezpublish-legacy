<form action={concat( $module.functions.groupedit.uri, '/', $classgroup.id)|ezurl} method="post" name="GroupEdit">
<div class="context-block">
<h2 class="context-title">{$classgroup.name|classgroup_icon( normal, $classgroup.name )}&nbsp;{'Edit <%group_name> [Class group]'|i18n( 'design/admin/class/groupedit',, hash( '%group_name', $classgroup.name ) )|wash}</h2>

<div class="context-attributes">
<div class="block">
<label>{'Name'|i18n( 'design/admin/class/groupedit' )}</label>
{include uri="design:gui/lineedit.tpl" name=Name id_name=Group_name value=$classgroup.name}
</div>
</div>

<div class="controlbar">
<div class="block">
<input class="button" type="submit" name="StoreButton" value={'OK'|i18n( 'design/admin/class/groupedit' )} />
<input class="button" type="submit" name="DiscardButton" value={'Cancel'|i18n( 'design/admin/class/groupedit' )} />
</div>
</div>

</div>
</form>

