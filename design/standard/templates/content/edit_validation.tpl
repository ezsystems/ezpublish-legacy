    {section show=$validation.processed}
        {section show=or($validation.attributes,$validation.placement)}

          <div class="warning">
          {section show=and($validation.attributes,$validation.placement)}
            <h2>{"Validation failed"|i18n("design/standard/content/edit")}</h2>
          {section-else}
            {section show=$validation.attributes}
            <h2>{"Input did not validate"|i18n("design/standard/content/edit")}</h2>
            {section-else}
            <h2>{"Location did not validate"|i18n("design/standard/content/edit")}</h2>
            {/section}
          {/section}
          <ul>
          {section name=UnvalidatedPlacements loop=$validation.placement show=$validation.placement}
            <li>{$:item.text}</li>
          {/section}
          {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
            <li>{$:item.name}: {$:item.description}</li>
          {/section}
          </ul>
          </div>

        {section-else}

          <div class="feedback">
          <h2>{"Input was stored successfully"|i18n("design/standard/content/edit")}</h2>
          {section name=ValidationLog loop=$validation_log show=$validation_log}
          <h4>{$:item.name}:</h4>
          <ul>
            {$:item.description}
          </ul>
          {/section}
          </div>

        {/section}

    {/section}
