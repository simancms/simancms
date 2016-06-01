{if $modules[$index].mode eq 'galleries'}
{include file="block_begin.tpl"}
<div class="galleries-container">
{section name=i loop=$modules[$index].galleries}
	<div class="gallery-item gallery-{$modules[$index].galleries[i].id}">
		<div class="gallery-image-container"><a href="{$modules[$index].galleries[i].url}"><img src="{$modules[$index].galleries[i].image}" class="gallery-image" border="0" /></a></div>
		<div class="gallery-title"><a href="{$modules[$index].galleries[i].url}">{$modules[$index].galleries[i].title}</a></div>
	</div>
	{if $modules[$index].galleries[i].newrow}<div style="clear:both;"></div>{/if}
{/section}
</div>
<div class="galleries-container-separator"></div>
{include file="pagebar.tpl"}
{include file="block_end.tpl"}
{/if}

{if $modules[$index].mode eq 'editorinsert'}
{include file="block_begin.tpl"}
	{section name=i loop=$modules[$index].galleries}
	<div class="gallery-insert-item gallery-insert-{$modules[$index].galleries[i].id}">
		<div class="gallery-insert-image-container"><a href="{$modules[$index].galleries[i].url}" onclick="{$modules[$index].galleries[i].onclick}"><img src="{$modules[$index].galleries[i].image}" class="gallery-insert-image" border="0" /></a></div>
		<div class="gallery-insert-title"><a href="{$modules[$index].galleries[i].url}">{$modules[$index].galleries[i].title}</a></div>
	</div>
		{if $modules[$index].galleries[i].newrow}<div style="clear:both;"></div>{/if}
	{/section}
{include file="block_end.tpl"}
{/if}