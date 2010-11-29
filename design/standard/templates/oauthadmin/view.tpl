{* Main window *}

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header">

<h1 class="context-title" title="{'Application name'|i18n( 'design/admin/class/view' )}">
    {'Application <%application_name>'|i18n( 'extension/oauthadmin',, hash( '%application_name', $application.name ) )|wash}
</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}
<div class="box-content">

<div class="context-information">
{if $application.updated|ne(0)}
    {def $modified=$application.updated}
{else}
    {def $modified=$application.created}
{/if}
<p class="left modified">{'Last modified'|i18n( 'extension/oauthadmin' )}:&nbsp;{$modified|l10n( shortdatetime )} by {$application.owner.contentobject.name|wash}</p>
{undef $modified}
<div class="break"></div>
</div>

<div class="context-attributes">

    {* Name. *}
    <div class="block">
    <h6>{'Name'|i18n( 'extension/oauthadmin' )}:</h6>
    {$application.name|wash}
    </div>

    {* Description. *}
    <div class="block">
    <h6>{'Description'|i18n( 'extension/oauthadmin' )}:</h6>
    {$application.description|wash}
    </div>

    {* Identifier. *}
    <div class="block">
    <h6>{'Client identifier'|i18n( 'extension/oauthadmin' )}:</h6>
    {$application.client_id|wash}
    </div>

    {* Secret. *}
    <div class="block">
    <h6>{'Client secret'|i18n( 'extension/oauthadmin' )}:</h6>
    {$application.client_secret|wash}
    </div>

    {* Endpoint URI. *}
    <div class="block">
    <h6>{'Endpoint URI'|i18n( 'extension/oauthadmin' )}:</h6>
    {$application.endpoint_uri|wash}
    </div>

</div>

{* DESIGN: Content END *}</div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc">
    <form style="display: inline;" action={concat( $module.functions.edit.uri, '/', $application.id )|ezurl} method="post">
        <input class="defaultbutton" type="submit" name="EditButton" value="{'Edit'|i18n( 'extension/oauthadmin' )}" title="{'Edit this application.'|i18n( 'extension/oauthadmin' )}" />
    </form>
    <form style="display: inline;" action={$module.functions.action.uri|ezurl} method="post">
        <input type="hidden" name="DeleteIDArray[]" value="{$application.id}" />
        <input class="button" type="submit" name="DeleteApplicationListButton" value="{'Delete'|i18n( 'extension/oauthadmin' )}" title="{'Delete this application.'|i18n( 'extension/oauthadmin' )}" />
    </form>
    <form style="display: inline;" action={concat( $module.functions.list.uri )|ezurl} method="get">
        <input class="button" type="submit" name="Cancel" value="{'Back'|i18n( 'extension/oauthadmin' )}" title="{'Edit this application.'|i18n( 'extension/oauthadmin' )}" />
    </form>

{* DESIGN: Control bar END *}</div>
</div>

</div>

