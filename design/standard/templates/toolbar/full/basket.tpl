<div class="toolbox">
    <div class="toolbox-design">
{let basket=fetch( shop, basket )
     use_urlalias=ezini( 'URLTranslator', 'Translation' )|eq( 'enabled' )
     basket_items=$basket.items}
    <h2>{"Shopping basket"|i18n("design/shop/layout")}</h2>
    <div class="toolbox-content">
{section show=$basket_items}
    <ul>
        {section var=product loop=$basket_items sequence=array( odd, even )}
            <li>
            {$product.item.item_count} x <a href={cond( $use_urlalias, $product.item.item_object.contentobject.main_node.url_alias,
                                                        concat( "content/view/full/", $product.item.node_id ) )|ezurl}>{$product.item.object_name}</a>
            </li>
        {/section}
    </ul>
    <div class="price"><p>{$basket.total_inc_vat|l10n(currency)}</p></div>
    <p><a href={"/shop/basket"|ezurl}>{"View all details"|i18n("design/shop/layout")}</a></p>
    {section-else}
        <p>{"Your basket is empty"|i18n("design/shop/layout")}</p>
    {/section}
    {/let}
    </div>
    </div>
</div>