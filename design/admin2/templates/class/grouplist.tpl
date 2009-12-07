<form name="GroupList" method="post" action={'class/grouplist'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Class groups [%group_count]'|i18n( 'design/admin/class/grouplist',, hash( '%group_count', $groups|count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0" summary="{'List of class groups'|i18n( 'design/admin/class/grouplist' )}">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/class/grouplist' )}" title="{'Invert selection.'|i18n( 'design/admin/class/grouplist' )}" onclick="ezjs_toggleCheckboxes( document.GroupList, 'DeleteIDArray[]' ); return false;"/></th>
    <th>{'Name'|i18n( 'design/admin/class/grouplist' )}</th>
    <th>{'Modifier'|i18n( 'design/admin/class/grouplist' )}</th>
    <th>{'Modified'|i18n( 'design/admin/class/grouplist' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Groups loop=$groups sequence=array( bglight, bgdark )}
<tr class="{$Groups.sequence}">

    {* Remove. *}
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$Groups.item.id}" title="{'Select class group for removal.'|i18n( 'design/admin/class/grouplist' )}" /></td>

    {* Name. *}
    <td>{$Groups.item.name|wash|classgroup_icon( small, $Groups.item.name|wash )}&nbsp;<a href={concat( $module.functions.classlist.uri, '/', $Groups.item.id)|ezurl}>{$Groups.item.name|wash}</a></td>

    {* Modifier. *}
    <td><a href={$Groups.item.modifier.contentobject.main_node.url_alias|ezurl}>{$Groups.item.modifier.contentobject.name|wash}</a></td>

    {* Modified. *}
    <td>{$Groups.item.modified|l10n( shortdatetime )}</td>

    {* Edit. *}
    <td><a href={concat( $module.functions.groupedit.uri, '/', $Groups.item.id )|ezurl}><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/class/grouplist' )}" title="{'Edit the <%class_group_name> class group.'|i18n( 'design/admin/class/grouplist',, hash( '%class_group_name', $Groups.item.name ) )|wash}" /></a></td>

</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="RemoveGroupButton" value="{'Remove selected'|i18n( 'design/admin/class/grouplist' )}" title="{'Remove the selected class groups. This will also remove all classes that only exist within the selected groups.'|i18n( 'design/admin/class/grouplist' )}" />
    <input class="button" type="submit" name="NewGroupButton" value="{'New class group'|i18n( 'design/admin/class/grouplist' )}" title="{'Create a new class group.'|i18n( 'design/admin/class/grouplist' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>


<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Recently modified classes'|i18n( 'design/admin/class/grouplist' )}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{let latest_classes=fetch( class, latest_list, hash( limit, 10 ) )}

{section show=$latest_classes}

    <table class="list" cellspacing="0" summary="{'List of recently modified classes'|i18n( 'design/admin/class/grouplist' )}">
    <tr>
        <th>{'Name'|i18n( 'design/admin/class/grouplist')}</th>
        <th class="tight">{'ID'|i18n( 'design/admin/class/grouplist' )}</th>
        <th>{'Identifier'|i18n( 'design/admin/class/grouplist' )}</th>
        <th>{'Modifier'|i18n( 'design/admin/class/grouplist' )}</th>
        <th>{'Modified'|i18n( 'design/admin/class/grouplist' )}</th>
        <th class="tight">&nbsp;</th>
    </tr>

    {section var=LatestClasses loop=$latest_classes sequence=array( bglight, bgdark )}
        <tr class="{$LatestClasses.sequence}">

            {* Name. *}
            <td>{$LatestClasses.identifier|class_icon( small, $LatestClasses.name|wash )}&nbsp;<a href={concat( '/class/view/', $LatestClasses.item.id )|ezurl}>{$LatestClasses.item.name|wash}</a></td>

            {* ID. *}
            <td class="number" align="right">{$LatestClasses.item.id}</td>

            {* Identifier. *}
            <td>{$LatestClasses.item.identifier|wash}</td>

            {* Modifier. *}
            <td><a href={$LatestClasses.item.modifier.contentobject.main_node.url_alias|ezurl}>{$LatestClasses.item.modifier.contentobject.name|wash}</a></td>

            {* Modified. *}
            <td>{$LatestClasses.item.modified|l10n(shortdatetime)}</td>

            {* Edit. *}
            <td><a href={concat( 'class/edit/', $LatestClasses.item.id, '/(language)/', $LatestClasses.item.top_priority_language_locale )|ezurl}><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/class/grouplist' )}" title="{'Edit the <%class_name> class.'|i18n( 'design/admin/class/grouplist',, hash( '%class_name', $LatestClasses.item.name) )|wash}" /></a></td>

        </tr>
    {/section}
    </table>

{/section}

{/let}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</form>
