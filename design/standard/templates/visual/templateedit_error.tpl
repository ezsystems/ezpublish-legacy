<div class="message-warning">

<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The template cannot be edited.'|i18n( 'design/standard/visual/templateedit' )}</h2>

{if $template_exists}
    {if $is_readable}
    <p>{'The web server does not have write access to the requested template.'|i18n( 'design/standard/visual/templateedit' )}</p>
    {else}
    <p>{'The web server does not have read access to the requested template.'|i18n( 'design/standard/visual/templateedit' )}</p>
    {/if}
{else}
    <p>{'The requested template does not exist or is not being used as an override.'|i18n( 'design/standard/visual/templateedit' )}</p>
{/if}

</div>

<form action={concat( 'visual/templateedit/', $template )|ezurl} method="post">

{if $original_template}
<input type="hidden" name="RedirectToURI" value="{concat( '/visual/templateview', $original_template )}" />
{else}
<input type="hidden" name="RedirectToURI" value="/visual/templatelist" />
{/if}


<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Edit <%template_name> [Template]'|i18n( 'design/standard/visual/templateedit',, hash( '%template_name', $template ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Requested template'|i18n( 'design/standard/visual/templateedit' )}:</label>
{$template|wash}
</div>

<div class="block">
<label>{'Siteaccess'|i18n( 'design/standard/visual/templateedit' )}:</label>
{$site_access|wash}
</div>

{if $original_template}
<div class="block">
<label>{'Override template'|i18n( 'design/standard/visual/templateedit' )}:</label>
<a href={concat( 'visual/templateview', $original_template )|ezurl}>{$original_template|wash}</a>
</div>
{/if}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input {if or( not( $template_exists ), not( $is_readable ) )}class="button-disabled" disabled="disabled"{else}class="button"{/if} type="submit" name="OpenReadOnly" value="{'Open as read only'|i18n( 'design/standard/visual/templateedit' )}" />
<input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n( 'design/standard/visual/templateedit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
