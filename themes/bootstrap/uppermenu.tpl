          <div class="nav-collapse">
            <ul class="nav">
			{section name=i loop=$special.uppermenu}
			{if $special.uppermenu[i].level eq 1}
				<li{if $special.uppermenu[i].is_submenu eq 1} class="dropdown"{elseif $special.uppermenu[i].active eq "1"} class="active"{/if} id="uppermenu{$smarty.section.i.index}">
					<a{if $special.uppermenu[i].is_submenu eq 1} class="dropdown-toggle" data-toggle="dropdown" href="#uppermenu{$smarty.section.i.index}"{else} href="{$special.uppermenu[i].url}{/if}"{if $special.uppermenu[i].alt neq ""} alt="{$special.uppermenu[i].alt}"{/if}{if $special.uppermenu[i].newpage eq "1"} target="_blank"{/if}{$special.uppermenu[i].attr}>
						{$special.uppermenu[i].caption}
						{if $special.uppermenu[i].is_submenu eq 1} 
							<b class="caret"></b>
						{/if}
					</a>
					<ul class="dropdown-menu">
						{section name=j loop=$special.uppermenu start=i}
						 {if $special.uppermenu[j].level gt "1" and $special.uppermenu[j].submenu_from eq $special.uppermenu[i].id}
							<li><a href="{$special.uppermenu[j].url}" class="{if $special.uppermenu[j].active eq "1"}upperMenuSelected{else}upperMenuLine{/if}"{if $special.uppermenu[j].alt neq ""} alt="{$special.uppermenu[j].alt}"{/if}{if $special.uppermenu[j].newpage eq "1"} target="_blank"{/if}{$special.uppermenu[j].attr}>{$special.uppermenu[j].caption}</a></li>
						 {/if}
						{/section}
					</ul>
					
				</li>
			{/if}
			{/section}
			</ul>
		</div><!--/.nav-collapse -->

{*
<table width="100%" cellspacing="0" cellpadding="0"><tr><td class="uppermenutabs">
{section name=i loop=$special.uppermenu}
{if $special.uppermenu[i].level eq 1}
<div class="{if $special.uppermenu[i].active eq "1"}upperMenuSelectedMain{else}upperMenuLineMain{/if}">{if $special.uppermenu[i].url neq ""}<a href="{$special.uppermenu[i].url}"{if $special.uppermenu[i].alt neq ""} alt="{$special.uppermenu[i].alt}"{/if}{if $special.uppermenu[i].newpage eq "1"} target="_blank"{/if}{$special.uppermenu[i].attr}>{$special.uppermenu[i].caption}</a>{else}{$special.uppermenu[i].caption}{/if}</div>
{/if}
{/section}
</td></tr><tr><td class="uppermenulines">
{section name=i loop=$special.uppermenu}
{if $special.uppermenu[i].level eq 1 and $special.uppermenu[i].is_submenu eq 1 and $special.uppermenu[i].active eq "1"}
   {section name=j loop=$special.uppermenu start=i}
    {if $special.uppermenu[j].level gt "1" and $special.uppermenu[j].submenu_from eq $special.uppermenu[i].id}
		<a href="{$special.uppermenu[j].url}" class="{if $special.uppermenu[j].active eq "1"}upperMenuSelected{else}upperMenuLine{/if}"{if $special.uppermenu[j].alt neq ""} alt="{$special.uppermenu[j].alt}"{/if}{if $special.uppermenu[j].newpage eq "1"} target="_blank"{/if}{$special.uppermenu[j].attr}>{$special.uppermenu[j].caption}</a>
    {/if}
   {/section}
{/if}
{/section}
</td></tr></table>
*}

{* Minimalistic style
{section name=i loop=$special.uppermenu}
{if $smarty.section.i.index eq 0}{else}&nbsp;&nbsp;{/if}
<a href="{$special.uppermenu[i].url}" class="{if $special.uppermenu[i].active eq "1"}upperMenuSelected{else}upperMenuLine{/if}"{if $special.uppermenu[i].alt neq ""} alt="{$special.uppermenu[i].alt}"{/if}{if $special.uppermenu[i].newpage eq "1"} target="_blank"{/if}{$special.uppermenu[i].attr}>{$special.uppermenu[i].caption}</a>
{/section}
*}