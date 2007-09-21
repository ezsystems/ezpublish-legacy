<form action={concat( 'content/restore/', $object.id )|ezurl} method="post" name="ObjectRestore">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{$object.class_identifier|class_icon( normal, $object.class_name )}{'Restoring object <%name> [%className]'|i18n( 'design/admin/content/restore',, hash( '%name', $object.name, '%className', $object.class_name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
    <p>{'The system will let you restore the object <%name>. Specify where you wish to restore it.'|i18n( 'design/admin/node/removeobject',, hash( '%name', $object.name ) )|wash}</p>
</div>

<div class="block">
    {if $location}
        <p><input type="radio" id="restore-type-1" name="RestoreType" value="1" checked="checked" title="{'The system will restore the original location of the object.'|i18n( 'design/admin/content/restore' )|wash}" /><label class="radio" for="restore-type-1" title="{'The system will restore the original location of the object.'|i18n( 'design/admin/content/restore' )|wash}">{'Restore original location <%nodeName>'|i18n( 'design/admin/content/restore',, hash( '%nodeName', $location.parent_node_obj.name ) )|wash}</p>
        <p><input type="radio" id="restore-type-2" name="RestoreType" value="2" title="{'The system will prompt you to browse for a location for the object.'|i18n( 'design/admin/content/restore' )|wash}" /><label class="radio" for="restore-type-2" title="{'The system will prompt you to browse for a location for the object.'|i18n( 'design/admin/content/restore' )|wash}">{'Browse for location'|i18n( 'design/admin/content/restore' )}</p>
    {else}
        <p><input type="radio" class="disabled" disabled="disabled" id="restore-type-1" name="RestoreType" value="1" title="{'The system will restore the original location of the object.'|i18n( 'design/admin/content/restore' )|wash}" /><label class="radio disabled" for="restore-type-1" title="{'The system will restore the original location of the object.'|i18n( 'design/admin/content/restore' )|wash}">{'Restore original locations'|i18n( 'design/admin/content/restore' )}</p>
        <p><input type="radio" id="restore-type-2" name="RestoreType" value="2" checked="checked" title="{'The system will prompt you to browse for a location for the object.'|i18n( 'design/admin/content/restore' )|wash}" /><label class="radio" for="restore-type-2" title="{'The system will prompt you to browse for a location for the object.'|i18n( 'design/admin/content/restore' )|wash}">{'Browse for location'|i18n( 'design/admin/content/restore' )}</p>
    {/if}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

    <input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/content/restore' )}" title="{'Restore <%name> to the specified location.'|i18n( 'design/admin/content/restore',, hash( '%name', $object.name ) )|wash}" />

    <input type="submit" class="button" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/content/restore' )}" title="{'Do not restore <%name> and return to trash.'|i18n( 'design/admin/content/restore',, hash( '%name', $object.name ) )|wash}" />
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>
