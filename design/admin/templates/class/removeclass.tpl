<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{section show=$DeleteResult|count|gt(1)}
    <h1 class="context-title">{'Remove classes?'|i18n( 'design/admin/class/removeclass' )}</h1>
{section-else}
    <h1 class="context-title">{'Remove class?'|i18n( 'design/admin/class/removeclass' )}</h1>
{/section}

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

{section show=$can_remove}
    {section show=$DeleteResult|count|gt(1)}
        <h2>{'Are you sure you want to remove these classes?'|i18n( 'design/admin/class/removeclass' )}</h2>
        {section-else}
        <h2>{'Are you sure you want to remove this class?'|i18n( 'design/admin/class/removeclass' )}</h2>
    {/section}
{section-else}
    <h2>{'Removal is not possible'|i18n( 'design/admin/class/removeclass' )}</h2>
{/section}

{section show=$already_removed}
{let class_list=''}
{section var=class loop=$already_removed}
{set class_list=concat( $class_list, $class.name )}
{delimiter}{set class_list=concat( $class_list, " ," )}{/delimiter}
{/section}
{section show=count( $already_removed )|eq( 1 )}
{"The class %1 was already removed from the group but still exists in others."|i18n( "design/admin/class/removeclass",, array( $class_list ) )}
{section-else}
{"The classes %1 were already removed from the group but still exist in others."|i18n( "design/admin/class/removeclass",, array( $class_list ) )}
{/section}
{/let}
{/section}

<ul>
{section var=Classes loop=$DeleteResult}
    {section show=$Classes.item.objectCount|gt( 0 )}
        {section show=$Classes.item.objectCount|eq( 1 )}
            <li>{"Removing class '%1' will result in the removal of %2 object!"|i18n( 'design/admin/class/removeclass',, array( $Classes.item.className|wash, $Classes.item.objectCount ) )}</li>
        {section-else}
            <li>{"Removing class '%1' will result in the removal of %2 objects!"|i18n( 'design/admin/class/removeclass',, array( $Classes.item.className|wash, $Classes.item.objectCount ) )}</li>
        {/section}
    {/section}
    {section show=$Classes.item.is_removable|not}
    <li>{$Classes.item.reason.text|wash}
        <ul>
            {section var=reason loop=$Classes.item.reason.list}
                {section show=is_set( $reason.list )}
                    <li>{$reason.text|wash}
                    <ul>
                        {section var=sub_reason loop=$reason.list}
                            <li>{$sub_reason.text|wash}</li>
                        {/section}
                    </ul>
                    </li>
                {section-else}
                    <li>{$reason.text|wash}</li>
                {/section}
            {/section}
        </ul>
    </li>
    {/section}
{*    <li>{"Class '%1' can NOT be removed since one of its object is used as system node!"|i18n( 'design/admin/class/removeclass',, array( $Classes.item.className|wash ) )}</li>*}
{/section}
</ul>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

<form action={concat( $module.functions.removeclass.uri, '/', $GroupID )|ezurl} method="post" name="ClassRemove">
    {section show=$can_remove}
        <input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/class/removeclass' )}" />
    {/section}
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/class/removeclass' )}" />
</form>

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>

