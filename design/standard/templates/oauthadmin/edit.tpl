{* Main window *}
<form action={concat( 'oauthadmin/edit/', $application.id )|ezurl} method="post" id="ClassEdit" name="ApplicationEdit">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header">

<h1 class="context-title" title="{'Application name'|i18n( 'design/admin/class/view' )}">
    {'Edit application <%application_name>'|i18n( 'extension/oauthadmin',, hash( '%application_name', $application.name ) )|wash}
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
    <label for="ApplicationName">{'Name'|i18n( 'extension/oauthadmin' )}:</label>
    <input class="box" type="text" id="ApplicationName" name="Name" size="30" value="{$application.name|wash}" title="{'Use this field to set the application name.'|i18n( 'extension/oauthadmin' )|wash}" />
    </div>

    {* Description. *}
    <div class="block">
    <label for="ApplicationDescription">{'Description'|i18n( 'extension/oauthadmin' )}:</label>
    <input class="box" type="text" id="ApplicationDescription" name="Description" size="30" value="{$application.description|wash}" title="{'Use this field to set the informal application description.'|i18n( 'extension/oauthadmin' )|wash}" />
    </div>

    {* Endpoint URI. *}
    <div class="block">
    <label for="ApplicationEndpointUri">{'Endpoint URI'|i18n( 'extension/oauthadmin' )}:</label>
    <input class="box" type="text" id="ApplicationEndpointUri" name="EndPointURI" size="30" value="{$application.endpoint_uri|wash}" title="{'Use this field to set the application endpoint URI.'|i18n( 'extension/oauthadmin' )|wash}" />
    </div>

</div>

{* DESIGN: Content END *}</div>

<div id="controlbar-bottom" class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<div class="element">
    <input class="defaultbutton" type="submit" name="StoreButton" value="{'OK'|i18n( 'extension/oauthadmin' )}" title="{'Store changes and exit from edit mode.'|i18n( 'design/admin/class/edit' )|wash}" />

    <input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/class/edit' )}" title="{'Discard all changes and exit from edit mode.'|i18n( 'design/admin/class/edit' )|wash}" />
</div>
<div class="float-break"></div>
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>
