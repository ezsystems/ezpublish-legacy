{* Main window *}

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header">

<h1 class="context-title" title="{'Confirm removal'|i18n( 'design/admin/class/view' )}">
    {'Confirm removal of application <%application_name>'|i18n( 'extension/oauthadmin',, hash( '%application_name', $application.name ) )|wash}
</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}
<div class="box-content">

<div class="context-information">
{if $application.modified|ne(0)}
    {def $modified=$application.modified}
{else}
    {def $modified=$application.created}
{/if}
<p class="left modified">{'Last modified'|i18n( 'extension/oauthadmin' )}:&nbsp;{$modified|l10n( shortdatetime )} by {$application.owner.contentobject.name|wash}</p>
{undef $modified}
<div class="break"></div>
</div>

    <div class="block">
        {"Are you sure you want to remove this application ?"|i18n( 'extension/oauthadmin' )}

        <form action={concat( $module.functions.list.uri )|ezurl} method="get">
            <input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n( 'extension/oauthadmin' )}" title="{'Edit this application.'|i18n( 'extension/oauthadmin' )}" />
        </form>
        <form action={$module.functions.action.uri|ezurl} method="post">
            <input type="hidden" name="ApplicationID" value="{$application.id}" />
            <input type="hidden" name="ConfirmDelete" value="1" />
            <input class="defaultbutton" type="submit" name="DeleteApplicationButton" value="{'Confirm'|i18n( 'extension/oauthadmin' )}" title="{'Confirm removal of this application.'|i18n( 'extension/oauthadmin' )}" />
        </form>
    </div>

</div>

{* DESIGN: Content END *}</div>

<div class="block">
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc">

{* DESIGN: Control bar END *}</div>
</div>
</div>

</div>

</form>
