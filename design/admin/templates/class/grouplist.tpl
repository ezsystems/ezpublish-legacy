<form action={'class/grouplist'|ezurl} method="post" name="GroupList">
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'Class groups [%group_count]'|i18n( 'design/admin/class/list',, hash( '%group_count', $groups|count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/class/list' )}" title="{'Invert selection.'|i18n( 'design/admin/class/list' )}" onclick="ezjs_toggleCheckboxes( document.GroupList, 'DeleteIDArray[]' ); return false;"/></th>
    <th>{'Name'|i18n( 'design/admin/class/list' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/class/list' )}</th>
    <th>{'Modified'|i18n( 'design/admin/class/list' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Groups loop=$groups sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$Groups.item.id}"></td>

    <td>{$Groups.item.name|classgroup_icon( small, $Groups.item.name )}&nbsp;<a href={concat( $module.functions.classlist.uri, '/', $Groups.item.id)|ezurl}>{$Groups.item.name|wash}</a></td>
    <td>{content_view_gui view=text_linked content_object=$Groups.item.modifier.contentobject}</td>
    <td>{$Groups.item.modified|l10n( shortdatetime )}</td>
    <td><a href={concat( $module.functions.groupedit.uri, '/', $Groups.item.id )|ezurl}><img class="button" src={'edit.png'|ezimage} width="16" height="16" alt="Edit" /></a></td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="RemoveGroupButton" value="{'Remove selected'|i18n( 'design/admin/class/list' )}" />
    <input class="button" type="submit" name="NewGroupButton" value="{'New class group'|i18n( 'design/admin/class/list' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>


<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Recently modified classes'|i18n( 'design/admin/class/list' )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{let latest_classes=fetch( class, latest_list, hash( limit, 10 ) )}

{section show=$latest_classes}

    <table class="list" cellspacing="0">
    <tr>
        <th>{'Name'|i18n( 'design/admin/class/list')}</th>
        <th>{'ID'|i18n( 'design/admin/class/list' )}</th>
        <th>{'Identifier'|i18n( 'design/admin/class/list' )}</th>
        <th>{'Modifier'|i18n( 'design/admin/class/list' )}</th>
        <th>{'Modified'|i18n( 'design/admin/class/list' )}</th>
        <th class="tight">&nbsp;</th>
    </tr>

    {section var=LatestClasses loop=$latest_classes sequence=array( bglight, bgdark )}
        <tr class="{$LatestClasses.sequence}">
            <td>{$LatestClasses.identifier|class_icon( small, $LatestClasses.name )}&nbsp;<a href={concat( '/class/view/', $LatestClasses.item.id )|ezurl}>{$LatestClasses.item.name|wash}</a></td>
            <td>{$LatestClasses.item.id}</td>
            <td>{$LatestClasses.item.identifier|wash}</td>
            <td>{content_view_gui view=text_linked content_object=$LatestClasses.item.modifier.contentobject}</td>
            <td>{$LatestClasses.item.modified|l10n(shortdatetime)}</td>
            <td><a href={concat( 'class/edit/', $LatestClasses.item.id )|ezurl}><img class="button" src={'edit.png'|ezimage} width="16" height="16" alt="edit" /></a></td>
        </tr>
    {/section}
    </table>
{/section}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</form>

{/let}
