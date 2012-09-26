<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Confirm class group removal'|i18n( 'design/admin/class/removegroup' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

{if $groups_info|count|eq(1)}
    <h2>{'Are you sure you want to remove the class group?'|i18n( 'design/admin/class/removegroup' )}</h2>
{else}
    <h2>{'Are you sure you want to remove the class groups?'|i18n( 'design/admin/class/removegroup' )}</h2>
{/if}

{section var=ClassGroups loop=$groups_info}

{section show=$ClassGroups.item.class_list}

<p>{'The following classes will be removed from the <%group_name> class group'|i18n( 'design/admin/class/removegroup',, hash( '%group_name', $ClassGroups.item.group_name ) )|wash}:</p>

<ul>
{section var=Classes loop=$ClassGroups.item.class_list}
    <li>
        {$Classes.class_name}&nbsp;({'%objects objects will be removed'|i18n( 'design/admin/class/removegroup',, hash( '%objects', $Classes.item.object_count ) )|wash})
    </li>
{/section}
</ul>
{/section}

{/section}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">

<div class="block">

<form action={concat( $module.functions.removegroup.uri )|ezurl} method="post" name="GroupRemove">
    <input class="defaultbutton" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/class/removegroup' )}" />
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/class/removegroup' )}" />
</form>

</div>

{* DESIGN: Control bar END *}</div></div>

</div>

</div>
