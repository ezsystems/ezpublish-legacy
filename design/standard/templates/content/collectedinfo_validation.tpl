    {section show=and($validation.processed,$collection_attributes)}
        {section show=$validation.attributes}

          <div class="warning">
            <h2>{"Input did not validate"|i18n("design/standard/node/view")}</h2>
          <ul>
          {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
            <li>{$:item.name|wash}: {$:item.description}</li>
          {/section}
          </ul>
          </div>

        {/section}

    {/section}
