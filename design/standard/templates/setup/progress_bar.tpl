{*?template charset=latin1?*}

      <table class="layout" width="780" cellpadding="0" cellspacing="0" border="0">
        <tr>

          <td class="setup_noborder">
	      {section show=$progress|gt(0)}
	        <img src={"/design/standard/images/setup/eZ_setup_progress_bar_left_full.png"|ezroot} alt=""/></td>
	      {section-else}
	        <img src={"/design/standard/images/setup/eZ_setup_progress_bar_left.png"|ezroot} alt=""/></td>
	      {/section}

	  <td>

	    <table cellpadding="0" cellspacing="0" border="0" width="758">
	      <tr height="22">
	        {section show=$progress|gt(0)}
		  <td class="setup_progress_left" width="{$progress}%" /></td>
		{/section}
	        {section show=$progress|lt(100)}
		  <td class="setup_progress_right" width="{sub( 100, $progress )}%"></td>
                {/section}
	        </td>
	      </tr>
	    </table>

	  </td>
	  <td>
	    {section show=$progress|lt(100)}
	      <img src={"/design/standard/images/setup/eZ_setup_progress_bar_right.png"|ezroot} alt=""/></td>
	    {section-else}
	      <img src={"/design/standard/images/setup/eZ_setup_progress_bar_right_full.png"|ezroot} alt=""/></td>
	    {/section}

	</tr>
      </table>