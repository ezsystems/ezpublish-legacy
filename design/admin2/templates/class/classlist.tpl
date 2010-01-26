<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h1 class="context-title">{$group.name|wash|classgroup_icon( 'normal', $group.name|wash )}&nbsp;{'%group_name [Class group]'|i18n( 'design/admin/class/classlist',, hash( '%group_name', $group.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-information">
<p>{'Last modified'|i18n( 'design/admin/class/classlist' )}: {$group.modified|l10n( shortdatetime )}, {$group_modifier.name|wash}</p>
</div>

<div class="context-attributes">

<div class="block">
<label>{'ID'|i18n( 'design/admin/class/classlist' )}:</label>
{$group.id}
</div>

<div class="block">
<label>{'Name'|i18n( 'design/admin/class/classlist' )}:</label>
{$group.name|wash}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<form action={'class/grouplist'|ezurl} method="post" name="GroupList">
    <input type="hidden" name="DeleteIDArray[]" value="{$group.id}" />
    <input type="hidden" name="EditGroupID" value="{$group.id}" />
    <input class="button" type="submit" name="EditGroupButton" value="{'Edit'|i18n( 'design/admin/class/classlist' )}" title="{'Edit this class group.'|i18n( 'design/admin/class/classlist' )}" />
    <input class="button" type="submit" name="RemoveGroupButton" value="{'Remove'|i18n( 'design/admin/class/classlist' )}" title="{'Remove this class group.'|i18n( 'design/admin/class/classlist' )}" />
</form>
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>


<form action={concat( 'class/classlist/', $GroupID )|ezurl} method="post" name="ClassList">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
<h2 class="context-title"><a href={'/class/grouplist'|ezurl}><img src={'up-16x16-blue.png'|ezimage} alt="{'Back to class groups.'|i18n( 'design/admin/class/classlist' )}" title="{'Back to class groups.'|i18n( 'design/admin/class/classlist' )}" /></a>&nbsp;{'Classes inside <%group_name> [%class_count]'|i18n( 'design/admin/class/classlist',, hash( '%group_name', $group.name, '%class_count', $class_count ) )|wash}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$class_count}
<table class="list" cellspacing="0" summary="{'List of classes inside %group_name class group [%class_count]'|i18n( 'design/admin/class/classlist',, hash( '%group_name', $group.name, '%class_count', $class_count ) )|wash}">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/class/classlist' )}" title="{'Invert selection.'|i18n( 'design/admin/class/classlist' )}" onclick="ezjs_toggleCheckboxes( document.ClassList, 'DeleteIDArray[]' ); return false;" /></th>
    <th>{'Name'|i18n('design/admin/class/classlist')}</th>
    <th class="tight">{'ID'|i18n('design/admin/class/classlist')}</th>
    <th>{'Identifier'|i18n('design/admin/class/classlist')}</th>
    <th>{'Modifier'|i18n('design/admin/class/classlist')}</th>
    <th>{'Modified'|i18n('design/admin/class/classlist')}</th>
    <th>{'Objects'|i18n('design/admin/class/classlist')}</th>
    <th class="tight">&nbsp;</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Classes loop=$groupclasses sequence=array( bglight, bgdark )}
<tr class="{$Classes.sequence}">
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$Classes.item.id}" title="{'Select class for removal.'|i18n( 'design/admin/class/classlist' )}" /></td>
    <td>{$Classes.item.identifier|class_icon( small, $Classes.item.name|wash )}&nbsp;<a href={concat( "/class/view/", $Classes.item.id )|ezurl}>{$Classes.item.name|wash}</a></td>
    <td class="number" align="right">{$Classes.item.id}</td>
    <td>{$Classes.item.identifier|wash}</td>
    <td>{content_view_gui view=text_linked content_object=$Classes.item.modifier.contentobject}</td>
    <td>{$Classes.item.modified|l10n( shortdatetime )}</td>
    <td class="number" align="right">{$Classes.item.object_count}</td>
    <td><a href={concat( 'class/copy/', $Classes.item.id )|ezurl} title="{'Create a copy of the <%class_name> class.'|i18n( 'design/admin/class/classlist',, hash( '%class_name', $Classes.item.name ) )|wash}"><img class="button" src={'copy.gif'|ezimage} width="16" height="16" alt="copy" /></a></td>
    <td><a href={concat( 'class/edit/', $Classes.item.id, '/(language)/', $Classes.item.top_priority_language_locale )|ezurl} title="{'Edit the <%class_name> class.'|i18n( 'design/admin/class/classlist',, hash( '%class_name', $Classes.item.name ) )|wash}"><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="edit" /></a></td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'There are no classes in this group.'|i18n( 'design/admin/class/classlist' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
    <div class="button-left">
    <input type="hidden" name = "CurrentGroupID" value="{$GroupID}" />
    <input type="hidden" name = "CurrentGroupName" value="{$group.name|wash}" />

    {if $class_count}
    <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/class/classlist' )}" title="{'Remove selected classes from the <%class_group_name> class group.'|i18n( 'design/admin/class/classlist',, hash( '%class_group_name', $group.name ) )|wash}" />
    {else}
    <input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/class/classlist' )}" disabled="disabled" />
    {/if}
    </div>

    <div class="button-right">
    {def $languages=fetch( 'content', 'prioritized_languages' )}
    {if gt( $languages|count, 1 )}
        <select name="ClassLanguageCode" title="{'Use this menu to select the language you to want use then click the "New class" button. The item will be created within the current location.'|i18n( 'design/admin/class/classlist' )|wash()}">
            {foreach $languages as $language}
                <option value="{$language.locale|wash()}">{$language.name|wash()}</option>
            {/foreach}
        </select>
    {else}
        <input type="hidden" name="ClassLanguageCode" value="{$languages[0].locale|wash()}" />
    {/if}
    {undef $languages}

    <input class="button" type="submit" name="NewButton" value="{'New class'|i18n( 'design/admin/class/classlist' )}" title="{'Create a new class within the <%class_group_name> class group.'|i18n( 'design/admin/class/classlist',, hash( '%class_group_name', $group.name ) )|wash}" />
    </div>

    <div class="float-break"></div>


{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>

