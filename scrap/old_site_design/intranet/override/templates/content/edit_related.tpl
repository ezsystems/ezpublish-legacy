{* List of related objects in content edit *}

<div id="related">

    <div class="title">
        {"Related objects"|i18n("design/standard/content/edit")}
    </div>

    <div class="list">
        {section var=object loop=$related_contentobjects sequence=array( even, odd )}

            <div class="{$object.sequence}">
                <div class="item">
                    <div class="link">
                        {content_view_gui view=text_linked content_object=$object.item}
                    </div>

                    <div class="object_tag_example">
                        &lt;object id='{$object.item.id}' /&gt;
                    </div>

                    <div class="select">
                        <input class="selection" type="checkbox" name="DeleteRelationIDArray[]" value="{$Object:item.id}" />
                    </div>
                </div>
            </div>

        {/section}
    </div>

    <div class="controls">

        <input class="button" type="image" name="BrowseObjectButton" value="{'Find'|i18n('design/standard/content/edit')}" src={"find.png"|ezimage} />
        {section show=$related_contentobjects}
            <input class="button" type="image" name="DeleteRelationButton" value="{'Remove'|i18n('design/standard/content/edit')}" src={"trash.png"|ezimage} />
        {/section}

        <div class="classcreate">
            <select name="ClassID">
                {section var=class loop=$object.can_create_class_list}
                    <option value="{$class.item.id}">{$class.item.name}</option>
                {/section}
            </select>
            <input class="button" type="submit" name="NewButton" value="{'New'|i18n('design/standard/content/edit')}" />
        </div>

    </div>

</div>
