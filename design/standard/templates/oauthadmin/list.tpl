<form name="ApplicationList" method="post" action={'oauthadmin/action'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header">

<h1 class="context-title">{'REST applications (%applications_count)'|i18n( 'extension/oauthadmin',, hash( '%applications_count', $applications|count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">

<table class="list" cellspacing="0" summary="{'List of applications'|i18n( 'extension/oauthadmin' )}">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'extension/oauthadmin' )}" title="{'Invert selection.'|i18n( 'extension/oauthadmin' )}" onclick="ezjs_toggleCheckboxes( document.ApplicationList, 'DeleteIDArray[]' ); return false;"/></th>
    <th>{'Name'|i18n( 'extension/oauthadmin' )}</th>
    <th>{'Modifier'|i18n( 'extension/oauthadmin' )}</th>
    <th>{'Modified'|i18n( 'extension/oauthadmin' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{foreach $applications as $application sequence array( bglight, bgdark ) as $sequence}
<tr class="{$sequence}">

    {* Remove. *}
    <td><input type="checkbox" name="DeleteIDArray[]" value="{$application.id}" title="{'Select application for removal.'|i18n( 'extension/oauthadmin' )}" /></td>

    {* Name. *}
    <td><a href={concat( $module.functions.view.uri, '/', $application.id)|ezurl}>{$application.name|wash}</a></td>

    {* Modifier. *}
    <td>{$application.owner.contentobject.name|wash}</a></td>

    {* Modified. *}
    {if $application.updated|ne(0)}
        {def $modified=$application.updated}
    {else}
        {def $modified=$application.created}
    {/if}
    <td>{$modified|l10n( shortdatetime )}</td>
    {undef $modified}

    {* Edit. *}
    <td><a href={concat( $module.functions.edit.uri, '/', $application.id )|ezurl}><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'extension/oauthadmin' )}" title="{'Edit the <%application_name> application.'|i18n( 'extension/oauthadmin',, hash( '%application_name', $application.name ) )|wash}" /></a></td>

</tr>
{/foreach}
</table>

{* DESIGN: Content END *}</div>

{* DESIGN: Control bar START *}<div class="block"><div class="controlbar">

<div class="block">
    <input class="button" type="submit" name="DeleteApplicationListButton" value="{'Remove selected'|i18n( 'extension/oauthadmin' )}" title="{'Remove the selected applications.'|i18n( 'extension/oauthadmin' )}" />
    <input class="button" type="submit" name="NewApplicationButton" value="{'New application'|i18n( 'extension/oauthadmin' )}" title="{'Create a new application.'|i18n( 'extension/oauthadmin' )}" />
</div>

{* DESIGN: Control bar END *}</div></div>

</div>

</form>
