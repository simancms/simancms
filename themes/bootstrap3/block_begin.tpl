{if $modules[$index].borders_off neq "1"}
{if $modules[$index].panel eq "center"}
  {* center panel *}
		<div class="row" id="block{$index}body">
			<div class="col-md-12">
				<h2 id="block{$index}title">
					{if $modules[$index].block_image neq ""}<img src="{$modules[$index].block_image}"> {/if}
					{if $modules[$index].rewrite_title_to neq ""}{$modules[$index].rewrite_title_to}{elseif $panel_title neq ""}{$panel_title}{else}{$modules[$index].title}{/if}
				</h2>

{elseif $modules[$index].panel eq "1"}
		<div class="row" id="block{$index}body">
			<div class="col-md-12">
				<h3>
					{if $modules[$index].block_image neq ""}<img src="{$modules[$index].block_image}"> {/if}
					{if $modules[$index].rewrite_title_to neq ""}{$modules[$index].rewrite_title_to}{elseif $panel_title neq ""}{$panel_title}{else}{$modules[$index].title}{/if}
				</h3>
{else}
		<div class="col-md-3" id="block{$index}title">
			<h3>
				{if $modules[$index].block_image neq ""}<img src="{$modules[$index].block_image}"> {/if}
				{if $modules[$index].rewrite_title_to neq ""}{$modules[$index].rewrite_title_to}{elseif $panel_title neq ""}{$panel_title}{else}{$modules[$index].title}{/if}
			</h3>
{/if}
{/if}
{if $tmp.block[$index].blockstart neq 1}{$special.document.block[$index].blockstart}{assign var=tmp.block[$index].blockstart value=1}{/if}