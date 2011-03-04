{* Main window *}

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header">

<h1 class="context-title" title="{'Confirm removal'|i18n( 'design/admin/class/view' )}">
    {'Confirm removal'|i18n( 'extension/oauthadmin')|wash}
</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}
<div class="box-content">

<div class="message-confirmation">
{if $applications|count|eq(1)}
    <p>{'Are you sure you want to remove this application?'|i18n( 'extension/oauthadmin' )}</p>
{else}
    <p>{'Are you sure you want to remove these applications?'|i18n( 'extension/oauthadmin' )}</p>
{/if}

<ul>
{section var=application loop=$applications}
    <li>
        {$application.name|wash}
    </li>
{/section}
</ul>

</div>


{* DESIGN: Content END *}</div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc">
    <form style="display: inline;" action={$module.functions.action.uri|ezurl} method="post">
        <input type="hidden" name="ConfirmDelete" value="1" />
{section var=application loop=$applications}
        <input type="hidden" name="DeleteIDArray[]" value="{$application.id}" />
{/section}
        <input class="defaultbutton" type="submit" name="DeleteApplicationListButton" value="{'Confirm'|i18n( 'extension/oauthadmin' )}" title="{'Confirm removal of these applications.'|i18n( 'extension/oauthadmin' )}" />
    </form>
    <form style="display: inline;" action={concat( $module.functions.list.uri )|ezurl} method="get">
        <input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n( 'extension/oauthadmin' )}" title="{'Edit this application.'|i18n( 'extension/oauthadmin' )}" />
    </form>
{* DESIGN: Control bar END *}</div>
</div>

</div>
