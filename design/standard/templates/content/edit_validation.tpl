    {section show=$validation.processed}
        {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}

        <div class="warning">
        <h2>{"Input did not validate"|i18n("design/standard/content/edit")}</h2>
        <ul>
         <li>{$UnvalidatedAttributes:item.identifier}: {$UnvalidatedAttributes:item.name}</li>
        </ul>
        </div>

        {section-else}
	   <div class="feedback">
	     <h2>{"Input was stored successfully"|i18n("design/standard/content/edit")}</h2>
	     {section name=ValidationLog loop=$validation_log show=$validation_log}
	     <h4>{$UnvalidatedAttributes:ValidationLog:item.identifier}:</h4>
	     <ul>
	       {$UnvalidatedAttributes:ValidationLog:item.message}
	     </ul>
	     {/section}
	   </div>
        {/section}
       {section name=UnvalidatedPlacements loop=$validation.placement show=$validation.placement}
        <div class="warning">
        <h2>{"Placement did not validate"|i18n("design/standard/content/edit")}</h2>
        <ul>
         <li>{$UnvalidatedPlacements:item.text}</li>
        </ul>
        </div>
	{section-else}
	   <div class="feedback">
	     <h2>{"Placements were stored successfully"|i18n("design/standard/content/edit")}</h2>
	   </div>  
        {/section}   

    {/section}
