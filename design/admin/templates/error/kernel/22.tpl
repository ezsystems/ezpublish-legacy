<div class="message-error">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The requested page could not be displayed. (22)'|i18n( 'design/admin/error/kernel' )}</h2>
{section show=$parameters.check.view_checked}
    <p>{'The requested view cannot be accessed.'|i18n( 'design/admin/error/kernel' )}</h2>
    <p>{'The <%view> within the <%module> is disabled and thus it cannot be accessed.'|i18n( 'design/admin/error/kernel',, hash( '%view', $parameters.check.view, '%module', $parameters.check.module ) )|wash}</li>
{section-else}
    <p>{'The requested module cannot be accessed.'|i18n( 'design/admin/error/kernel' )}</h2>
    <p>{'The <%module> module is disabled and thus it cannot be accessed.'|i18n( 'design/admin/error/kernel',, hash( '%module', $parameters.check.module ) )|wash}</h2>
{/section}
</div>


{section show=$embed_content}
    {$embed_content}
{/section}
