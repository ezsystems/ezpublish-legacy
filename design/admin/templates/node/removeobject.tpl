<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Remove object?'|i18n( 'design/admin/node/removeobject' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

<h2>{"Are you sure you want to remove these items?"|i18n("design/admin/node/removeobject")}</h2>
<ul>
{section name=Result loop=$DeleteResult}
    {section show=$Result:item.childCount|gt(0)}
        <li>{"%nodename and its %childcount children. %additionalwarning"
             |i18n( 'design/admin/node/removeobject',,
                    hash( '%nodename', $Result:item.nodeName,
                          '%childcount', $Result:item.childCount,
                          '%additionalwarning', $Result:item.additionalWarning ) )}</li>
    {section-else}
        <li>{"%nodename %additionalwarning"
             |i18n( 'design/admin/node/removeobject',,
                    hash( '%nodename', $Result:item.nodeName,
                          '%additionalwarning', $Result:item.additionalWarning ) )}</li>
    {/section}
{/section}
</ul>

{section show=$moveToTrashAllowed}
  <input type="hidden" name="SupportsMoveToTrash" value="1" />
  <p><input type="checkbox" name="MoveToTrash" value="1" checked="checked" />{'Move to trash'|i18n('design/admin/node/removeobject')}</p>

  <p><b>{"Note:"|i18n("design/admin/node/removeobject")}</b> {"If %trashname is checked you will find the removed items in the trash afterwards."
                                                    |i18n( 'design/admin/node/removeobject',,
                                                           hash( '%trashname', concat( '<i>', 'Move to trash' | i18n( 'design/admin/node/removeobject' ), '</i>' ) ) )}</p>
  <br/>
{/section}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/admin/node/removeobject")}
{include uri="design:gui/defaultbutton.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/admin/node/removeobject")}

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

</form>
