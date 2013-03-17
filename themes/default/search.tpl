{if $modules[$index].mode eq "search"}
{include file="block_begin.tpl"}
<form action="index.php">
<input type="hidden" name="m" value="search">
<div align="center">
<input type="text" name="q" size="30" value="{$special.search_text}">{$modules[$index].formadditionalhtml}
<input type="submit" value="{$lang.module_search.go_search}">
</div>
</form>
{if $special.search_text neq ""}
{$lang.module_search.by_query} <strong>"{$special.search_text}"</strong> {$lang.module_search.find} <strong>{$modules[$index].result_count}</strong> {$lang.module_search.result_entries}<br />
<br />
{section name="i" loop=$modules[$index].search}
<a href="{$modules[$index].search[i].url}"><strong>{$modules[$index].search[i].title}</strong></a><br />
{$modules[$index].search[i].text}<br />
<font color="#bbbbbb">{$modules[$index].search[i].url}</font><br />
<br />
{/section}
{include file="pagebar.tpl"}
{/if}
{include file="block_end.tpl"}
{/if}		   

{if $modules[$index].mode eq "shortview"}
{include file="block_begin.tpl"}
<form action="index.php">
<input type="hidden" name="m" value="search">
<div align="center">
<input type="text" name="q" size="12" value="{$special.search_text}"><br />
{$modules[$index].formadditionalhtml}
<input type="submit" value="{$lang.module_search.go_search}">
</div>
</form>
{include file="block_end.tpl"}
{/if}		   

{if $modules[$index].mode eq "admin"}
{include file="block_begin.tpl"}
<a href="index.php?m=blocks&d=add&b=search&id=1&c={$lang.search}">{$lang.set_as_block} "{$lang.search}"</a>
<br />
<form action="index.php?m=menu&d=addouter" method="post">
<input type="hidden" name="p_url" value="index.php?m=search">
<input type="hidden" name="p_caption" value="{$lang.search}">
<input type="submit" value="{$lang.add_to_menu} - {$lang.search}">
</form>
{include file="block_end.tpl"}
{/if}
