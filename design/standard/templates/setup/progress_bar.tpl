{*?template charset=latin1?*}

      <table class="layout" width="780" cellpadding="0" cellspacing="0" border="0">
        <tr>

          <td class="setup_noborder">
	      {section show=$progress|gt(0)}
	        <img src={"/design/standard/images/setup/eZ_setup_progress_bar_left_full.png"|ezroot} alt=""/></td>
	      {section-else}
	        <img src={"/design/standard/images/setup/eZ_setup_progress_bar_left.png"|ezroot} alt=""/></td>
	      {/section}

          {section show=$progress|gt(0)}
	    <td class="setup_progress_left" width="{mul( 549, div( $progress, 100 ) )}" /></td>
	  {/section}
          {section show=$progress|lt(100)}
	    <td class="setup_progress_right" width="{mul( 549, div( sub( 100, $progress ), 100 ) )}"></td>
          {/section}
	  <td width="209" class="setup_progress_right">
	    <div class="setup_progress_bar_text">{$progress}% {"complemete"|i18n("design/standard/setup")}</div>
          </td>
 	  <td><img src={"/design/standard/images/setup/eZ_setup_progress_bar_right.png"|ezroot} alt=""/></td>

	</tr>
      </table>