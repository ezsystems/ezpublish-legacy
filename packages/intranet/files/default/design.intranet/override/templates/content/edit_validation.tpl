<div class="validation">
    {section show=$validation.processed}
        {section show=or($validation.attributes,$validation.placement)}

          <div class="warning">

          <div class="title">
          {section show=and($validation.attributes,$validation.placement)}
              <h1>{"Validation failed"|i18n("design/standard/content/edit")}</h1>
          {section-else}
          {section show=$validation.attributes}
              <h1>{"Input did not validate"|i18n("design/standard/content/edit")}</h1>
          {section-else}
              <h1>{"Location did not validate"|i18n("design/standard/content/edit")}</h1>
          {/section}
          {/section}
          </div>

          <div class="list">
          {section name=UnvalidatedPlacements loop=$validation.placement show=$validation.placement}
              <div class="item">
                  {$:item.text}
              </div>
          {/section}
          {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
              <div class="item">
                  {$:item.name|wash}: <div class="description">{$:item.description}</div>
              </div>
          {/section}
          </div>

          </div>

        {section-else}

          <div class="feedback">

          <div class="title">
              <h1>{"Input was stored successfully"|i18n("design/standard/content/edit")}</h1>
          </div>

          {section name=ValidationLog loop=$validation_log show=$validation_log}
          <div class="element">
              <h1>{$:item.name|wash}:</h1>
              <div class="list">
                  {section name=LogMessage loop=$:item.description}
                   <div class="item">
                       {$:item}
                   </div>
                   {/section}
               </div>
          </div>
          {/section}
          </div>

        {/section}

    {/section}
</div>
