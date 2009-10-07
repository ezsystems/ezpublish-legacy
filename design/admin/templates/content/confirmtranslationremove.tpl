<form name="TranlationRemove" method="post" action={concat( $module.functions.translations.uri )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Confirm language removal'|i18n( 'design/admin/content/confirmtranslationremove' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

{if $confirm_list|count|eq( 1 )}
<h2>{'Are you sure you want to remove the language?'|i18n( 'design/admin/content/confirmtranslationremove' )}</h2>
{else}
<h2>{'Are you sure you want to remove the languages?'|i18n( 'design/admin/content/confirmtranslationremove' )}</h2>
{/if}

<ul>
{section var=Translations loop=$confirm_list}
    <li>
      {'Removing <%1> will also result in the removal of %2 translated versions.'|i18n( 'design/admin/content/confirmtranslationremove',, hash( '%1', $Translations.item.translation.name|gt( 0 )|choose( concat( $Translations.item.translation.locale_object.language_name, ' ', $Translations.item.translation.locale_object.language_comment ), $Translations.item.translation.name|wash), '%2', $Translations.item.count ) )|wash}
      <input type="hidden" name="ConfirmTranlationID[]" value="{$Translations.item.translation.id}" />
    </li>
{/section}
</ul>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/content/confirmtranslationremove' )}" />
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/content/confirmtranslationremove' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
