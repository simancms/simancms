			<ul class="nav navbar-nav">
			{section name=i loop=$special.uppermenu}
			{if $special.uppermenu[i].level eq 1}
				{$special.uppermenu[i].html_begin}<li{if $special.uppermenu[i].is_submenu eq 1} class="dropdown"{elseif $special.uppermenu[i].active eq "1"} class="active"{/if} id="uppermenu{$smarty.section.i.index}">
					<a{if $special.uppermenu[i].is_submenu eq 1} class="dropdown-toggle" data-toggle="dropdown" href="#uppermenu{$smarty.section.i.index}"{else} href="{$special.uppermenu[i].url}{/if}"{if $special.uppermenu[i].alt neq ""} alt="{$special.uppermenu[i].alt}"{/if}{if $special.uppermenu[i].newpage eq "1"} target="_blank"{/if}{$special.uppermenu[i].attr}>
						{$special.uppermenu[i].caption}
						{if $special.uppermenu[i].is_submenu eq 1} 
							<b class="caret"></b>
						{/if}
					</a>
					<ul class="dropdown-menu">
						{section name=j loop=$special.uppermenu start=i}
						 {if $special.uppermenu[j].level gt "1" and $special.uppermenu[j].submenu_from eq $special.uppermenu[i].id}
							 {$special.uppermenu[j].html_begin}<li><a href="{$special.uppermenu[j].url}" class="{if $special.uppermenu[j].active eq "1"}upperMenuSelected{else}upperMenuLine{/if}"{if $special.uppermenu[j].alt neq ""} alt="{$special.uppermenu[j].alt}"{/if}{if $special.uppermenu[j].newpage eq "1"} target="_blank"{/if}{$special.uppermenu[j].attr}>{$special.uppermenu[j].caption}</a></li>{$special.uppermenu[j].html_end}
						 {/if}
						{/section}
					</ul>
					
				</li>{$special.uppermenu[i].html_end}
			{/if}
			{/section}
			</ul>
