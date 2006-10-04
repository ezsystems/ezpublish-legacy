{* Relations windows. *}
<a name="relations"></a>
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{def $related_objects_count = fetch( 'content', 'related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )}
{def $reverse_related_objects_count = fetch( 'content', 'reverse_related_objects_count', hash( 'object_id', $node.object.id , 'all_relations', true() ) )}
<h2 class="context-title">{'Relations [%relation_count]'|i18n( 'design/admin/node/view/full',, hash( '%relation_count', sum( $related_objects_count, $reverse_related_objects_count ) ) )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Related objects list. *}

<table class="list" cellspacing="0">
<tr>
    <th>{'Related objects [%related_objects_count]'|i18n( 'design/admin/node/view/full',, hash( '%related_objects_count', $related_objects_count ) )}</th>
    {if $related_objects_count}
    <th>{'Class'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Relation type'|i18n( 'design/admin/node/view/full')}</th>
    {/if}
</tr>
{if $related_objects_count}
    {def $related_objects_grouped = fetch( 'content', 'related_objects', hash( 'object_id', $node.object.id, 'all_relations', true(), 'group_by_attribute', true(), 'sort_by', array( array( 'class_identifier', true() ), array( 'name', true() ) ) ) )}
    {def $related_objects_id_typed = fetch( 'content', 'related_objects_ids', hash( 'object_id', $node.object.id ) )}

    {def $tr_class='bglight'}
    {def $attr = 0}
    {foreach $related_objects_grouped as $attribute_id => $related_objects_array }
        {if ne( $attribute_id, 0 )}
            {set $attr = fetch( 'content', 'class_attribute', hash( 'attribute_id', $attribute_id ) )}
        {/if}
        {foreach $related_objects_array as $object }
            <tr class="{$tr_class}">
            <td>{$object.content_class.identifier|class_icon( small, $object.content_class.name|wash )}&nbsp;{content_view_gui view=text_linked content_object=$object}</td>
            <td>{$object.content_class.name|wash}</td>
            <td>
                {foreach $related_objects_id_typed as $relationType => $relationIDArray}
                    {if $relationIDArray|contains($object.id)}
                        {if and( ne( $attribute_id, 0 ), eq( $relationType, 'attribute' ) )}
                            {$relationType||i18n( 'design/admin/node/view/full' )}( {$attr.name} )
                        {elseif and( eq( $attribute_id, 0 ), ne( $relationType, 'attribute' ) )}
                            {$relationType||i18n( 'design/admin/node/view/full' )}
                        {/if}
                    {/if}
                {/foreach}
            </td>
            </tr>
            {if eq( $tr_class,'bdark' )}
                {set $tr_class='bglight'}
            {else}
                {set $tr_class='bgdark'}
            {/if}
        {/foreach}
    {/foreach}
{else}
    <tr><td>{'The item being viewed does not make use of any other objects.'|i18n( 'design/admin/node/view/full' )}</td></tr>
{/if}
</table>

{* Reverse related objects list. *}

<table class="list" cellspacing="0">
<tr>
    <th>{'Reverse related objects [%related_objects_count]'|i18n( 'design/admin/node/view/full',, hash( '%related_objects_count', $reverse_related_objects_count ) )}</th>
    {if $reverse_related_objects_count}
    <th>{'Class'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Relation type'|i18n( 'design/admin/node/view/full' )}</th>
    {/if}
</tr>
{if $reverse_related_objects_count}
    {def $reverse_related_objects_grouped = fetch( 'content', 'reverse_related_objects', hash( 'object_id', $node.object.id, 'all_relations', true(), 'group_by_attribute', true(), 'sort_by', array( array( 'class_identifier', true() ), array( 'name', true() ) ) ) )}
    {def $reverse_related_objects_id_typed = fetch( 'content', 'reverse_related_objects_ids', hash( 'object_id', $node.object.id ) )}

    {def $tr_class='bglight'}
    {def $attr = 0}
    {foreach $reverse_related_objects_grouped as $attribute_id => $related_objects_array }
        {if ne( $attribute_id, 0 )}
            {set $attr = fetch( 'content', 'class_attribute', hash( 'attribute_id', $attribute_id ) )}
        {/if}
        {foreach $related_objects_array as $object }
            <tr class="{$tr_class}">
            <td>{$object.content_class.identifier|class_icon( small, $object.content_class.name|wash )}&nbsp;{content_view_gui view=text_linked content_object=$object}</td>
            <td>{$object.content_class.name|wash}</td>
            <td>
                {foreach $reverse_related_objects_id_typed as $relationType => $relationIDArray}
                    {if $relationIDArray|contains($object.id)}
                        {if and( ne( $attribute_id, 0 ), eq( $relationType, 'attribute' ) )}
                            {$relationType||i18n( 'design/admin/node/view/full' )}( {$attr.name} )
                        {elseif and( eq( $attribute_id, 0 ), ne( $relationType, 'attribute' ) )}
                            {$relationType||i18n( 'design/admin/node/view/full' )}
                        {/if}
                    {/if}
                {/foreach}
            </td>
            </tr>
            {if eq( $tr_class,'bdark' )}
                {set $tr_class='bglight'}
            {else}
                {set $tr_class='bgdark'}
            {/if}
        {/foreach}
    {/foreach}
{else}
    <tr><td>{'The item being viewed is not in use by any other objects.'|i18n( 'design/admin/node/view/full' )}</td></tr>
{/if}
</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
