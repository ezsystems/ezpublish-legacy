<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Translations [%translations]'|i18n( 'design/admin/node/view/full',, hash( '%translations', $node.object.current.language_list|count ) )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th>{'Language'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Locale'|i18n( 'design/admin/node/view/full' )}</th>
</tr>

{section var=Translations loop=$node.object.current.language_list sequence=array( bglight, bgdark )}
<tr class="{$Translations.sequence}">

{* Language name. *}
<td>
<img src="{$Translations.item.language_code|flag_icon}" alt="{$Translations.item.language_code}" />
&nbsp;
{section show=and( eq( $Translations.item.language_code, $language_code ), $node.object.current.language_list|gt( 1 ) )}
{$Translations.item.locale.intl_language_name}
{section-else}
<a href={concat( '/content/view/full/', $node.node_id, '/language/', $Translations.item.language_code )|ezurl} title="{'View translation.'|i18n( 'design/admin/node/view/full' )}">{$Translations.item.locale.intl_language_name}</a>
{/section}
</td>

{* Locale code. *}
<td>{$Translations.item.language_code}</td>

</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
