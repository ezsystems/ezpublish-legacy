<form action={concat($module.functions.translations.uri)|ezurl} method="post" name="TranlationRemove">
<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Remove translation?'|i18n( 'design/admin/content/confirmtranslationremove' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

<h2>{"Are you sure you want to remove this translation?"|i18n("design/admin/content/confirmtranslationremove")}</h2>
<ul>
{section name=Result loop=$confirm_list}
    <li>
      {"Removing '%1' will remove the translation itself and %2 translated versions!"|
       i18n("design/admin/content/confirmtranslationremove",,hash("%1",$:item.translation.name|gt(0)|choose(concat($:item.translation.locale_object.language_name," ",$:item.translation.locale_object.language_comment),$:item.translation.name|wash),
                                            "%2",$:item.count))}
      <input type="hidden" name="ConfirmTranlationID[]" value="{$Result:item.translation.id}" />
    </li>
{/section}
</ul>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="OK"|i18n("design/admin/content/confirmtranslationremove")}
{include uri="design:gui/button.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/admin/content/confirmtranslationremove")}

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>