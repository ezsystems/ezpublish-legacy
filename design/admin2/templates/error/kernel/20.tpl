<div class="message-error">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The requested page could not be displayed. (20)'|i18n( 'design/admin/error/kernel' )}</h2>
<p>{'The requested address or module could not be found.'|i18n( 'design/admin/error/kernel',, hash( '%module', $parameters.module ) )|wash}</p>
<p>{'Possible reasons'|i18n( 'design/admin/error/kernel' )}:</p>
<ul>
    <li>{'The address was misspelled. Try changing the URL.'|i18n( 'design/admin/error/kernel' )}</li>
    <li>{'The name of the module was misspelled. Try changing the URL.'|i18n( 'design/admin/error/kernel' )}</li>
    <li>{'There is no <%module> module available on this site.'|i18n( 'design/admin/error/kernel',, hash( '%module', $parameters.module ) )|wash}</li>
    <li>{'The site is using URL matching to determine which siteaccess to use, but the name of the siteaccess is missing from the URL. Try to add the name of the siteaccess; it should be specified before the name of the module.'|i18n( 'design/admin/error/kernel' )}</li>
</ul>
</div>

{if $embed_content}

{$embed_content}
{/if}
