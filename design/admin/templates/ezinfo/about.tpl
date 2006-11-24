<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'eZ Publish information'|i18n( 'design/admin/ezinfo/about' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="context-attributes">

<table class="list" cellspacing="0">

<tr>
    <th><label>{'What is eZ Publish?'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    {if is_set( $what_is_ez_publish )}
       {$what_is_ez_publish}
    {/if}
</td>
</tr>
</table>
<table class="list" cellspacing="0">
<tr>
    <th><label>{'Licence'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    {if is_set( $license )}
       {$license}
    {/if}
</td>
</tr>
</table>

<table class="list" cellspacing="0">
<tr>
<th><label>{'Contributors'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    {if is_set( $contributors )}
       {$contributors}
    {/if}
</td>
</tr>
</table>
<table class="list" cellspacing="0">
<tr>
    <th><label>{'Copyright Notice'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    {if is_set( $copyright_notice )}
       {$copyright_notice}
    {/if}
</td>
</tr>
</table>

<table class="list" cellspacing="0">
<tr>
    <th><label>{'Third-Party Software'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    {if is_set( $third_party_software )}
       {$third_party_software}
    {/if}
</td>
</tr>
</table>

<table class="list" cellspacing="0">
<tr>
<th><label>{'Extensions'|i18n( 'design/admin/setup/info' )}</label></th>
</tr>
<tr>
<td>
    {if is_set( $extensions )}
       {$extensions}
    {/if}
</td>
</tr>
</table>


</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>
