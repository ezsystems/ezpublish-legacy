<form action={concat( $module.functions.discountruleedit.uri, '/', $discountgroup_id, '/', $discountrule.id )|ezurl} method="post" name="DiscountRuleEdit">

<div class="context-block">
<h2 class="context-title">{'Edit <%rule_name> [Discount rule]'|i18n( 'design/admin/shop/discountruleedit',, hash( '%rule_name', $discountrule.name ) )|wash}</h2>

<div class="context-attributes">

    <div class="block">
        <label>{'Name'|i18n( 'design/admin/shop/discountruleedit' )}</label>
        <input type="text" name="discountrule_name" value="{$discountrule.name}" size=40>
    </div>

    <div class="block">
        <label>{'Discount percent'|i18n( 'design/admin/shop/discountruleedit' )}</label>
        <input type="text" name="discountrule_percent" value="{$discountrule.discount_percent|l10n( number )}" size="5" />&nbsp;%
    </div>

    <div class="block">
             <label>{"Class"|i18n( 'design/admin/shop/discountruleedit' )}</label>
             <select name="Contentclasses[]" size="5" multiple="multiple" >
                 <option value="-1" {section show=$class_any_selected}selected="selected"{/section} >{"Any"|i18n( 'design/admin/shop/discountruleedit' )}</option>
                 {section name=Classes loop=$product_class_list}
                     <option value="{$Classes:item.id}" {switch match=$Classes:item.id}{case in=$class_limitation_list} selected="selected"{/case}{case/}{/switch}>
                         {$Classes:item.name|wash}
                     </option>
                 {/section}
             </select>
    </div>

    <div class="block">
            <label>{"Section"|i18n( 'design/admin/shop/discountruleedit' )}</label>
            <select name="Sections[]" size="5" multiple="multiple" >
                 <option value="-1" {section show=$section_any_selected}selected="selected"{/section}>{"Any"|i18n( 'design/admin/shop/discountruleedit' )}</option>
                 {section name=Sections loop=$section_list}
                     <option value="{$Sections:item.id}" {switch match=$Sections:item.id}{case in=$section_limitation_list} selected="selected"{/case}{case/}{/switch}>
                        {$Sections:item.name|wash}
                    </option>
                 {/section}
            </select>
    </div>

    <div class="block">
             <label>{"Object"|i18n( 'design/admin/shop/discountruleedit' )}</label>
             <table>
             <tr>
<th>&nbsp;</th>
<th>Name</th>
             </tr>
             {section show=$product_list name=Products loop=$product_list}
             <tr>
                 <td><input type="checkbox" name="DeleteProductIDArray[]" value="{$Products:item.id}" /></td>
                 <td>{$Products:item.name|wash}</td>
             </tr>
             {section-else}
             <tr>
                 <td>
                     {'Not specified.'|i18n('design/standard/shop')}
                 </td>
             </tr>
             {/section}
             </table>

                 <input class="menubutton" type="image" name="BrowseProductButton" value="{'Find'|i18n('design/standard/shop')}" src={"find.png"|ezimage} />
                 {section show=$product_list}
                     <input class="menubutton" type="image" name="DeleteProductButton" value="{'Remove'|i18n('design/standard/shop')}" src={"trash.png"|ezimage} />
                 {/section}

     </div>

</div>

<div class="controlbar">
<div class="block">
    <input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/shop/discountruleedit' )}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/shop/discountruleedit' )}" />
</div>
</div>

</div>
</form>