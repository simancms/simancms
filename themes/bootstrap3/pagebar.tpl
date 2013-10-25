{if $modules[$index].pages.pages gt "1"}
	<ul class="pagination">
		{section name="i" loop=5000 max=$modules[$index].pages.pages start="1"}
			<li{if $smarty.section.i.index eq $modules[$index].pages.selected} class="active"{/if}>
				<a href="{$modules[$index].pages.url}&from={math equation="x*y" x=$modules[$index].pages.interval y=$smarty.section.i.index-1}">{$smarty.section.i.index}</a>
			</li>
		{/section}
	</ul>
{/if}