<div class="message-warning">

<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Template edit error'|i18n( 'design/admin/visual/templateedit' )}</h2>

{section show=$template_exists}
    {section show=$is_readable}
    <p>{'The template you requested exists but it is not writeable by the web server. You can proceed to open the file readonly.'|i18n( 'design/admin/visual/templateedit' )|wash}</p>
    {section-else}
    <p>{'The template you requested exists but it is not readable by the web server.'|i18n( 'design/admin/visual/templateedit' )|wash}</p>
    {/section}
    <p>{'Possible actions'|i18n( 'design/admin/visual/templateedit' )}:</p>
<ul>
    <li>{'Contact the system administrator to fix the permissions.'|i18n( 'design/admin/visual/templateedit' )}</li>
    <li>{'Use another way (e. g. shell or FTP access) to edit the template.'|i18n( 'design/admin/visual/templateedit' )}</li>
</ul>

{section-else}
    <p>{'The template you requested does not exist or does not override any template in the siteaccess.'|i18n( 'design/admin/visual/templateedit' )}</p>
    <p>{'Possible actions'|i18n( 'design/admin/visual/templateedit' )}:</p>
<ul>
    <li>{'Check the name of the template.'|i18n( 'design/admin/visual/templateedit' )}</li>
    <li>{'Choose another template to edit.'|i18n( 'design/admin/visual/templateedit' )}</li>
    <li>{'Click Cancel to get to the list of templates and select another template and/or siteaccess.'|i18n( 'design/admin/visual/templateedit' )}</li>
</ul>
{/section}

</div>


<form action={concat( 'visual/templateedit/', $template )|ezurl} method="post">

{section show=$original_template}
<input type="hidden" name="RedirectToURI" value="{concat( '/visual/templateview', $original_template )}" />
{section-else}
<input type="hidden" name="RedirectToURI" value="/visual/templatelist" />
{/section}


<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Edit <%template_name> [Template]'|i18n( 'design/admin/visual/templateedit',, hash( '%template_name', $template ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Requested template'|i18n( 'design/admin/visual/templateedit' )}</label>
{$template|wash}
</div>

<div class="block">
<label>{'Siteaccess'|i18n( 'design/admin/visual/templateedit' )}</label>
{$site_access|wash}
</div>

{section show=$original_template}
<div class="block">
<label>{'Overrides template'|i18n( 'design/admin/visual/templateedit' )}</label>
<a href={concat( 'visual/templateview', $original_template )|ezurl}>{$original_template|wash}</a>
</div>
{/section}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input {section show=or( not( $template_exists ), not( $is_readable ) )}class="button-disabled" disabled="disabled"{section-else}class="button"{/section} type="submit" name="OpenReadOnly" value="{'Proceed'|i18n( 'design/admin/visual/templateedit' )}" />
<input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n( 'design/admin/visual/templateedit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
