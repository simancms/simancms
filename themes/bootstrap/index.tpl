{include file="page_header.tpl"}
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="{if $special.page.scheme neq ""}{$special.page.scheme}{else}http{/if}://{$special.resource_url}" onclick="">{$_settings.resource_title}</a>
				{if $_settings.upper_menu_id neq ""}
					{include file="uppermenu.tpl"}
				{/if}
			<form class="navbar-search pull-right" action="index.php">
				<input type="hidden" name="m" value="{if $_settings.search_module neq ""}{$_settings.search_module}{else}search{/if}">
				<input type="hidden" name="d" value="{if $_settings.search_action neq ""}{$_settings.search_action}{else}{/if}">
				<input name="{if $_settings.search_query_var neq ""}{$_settings.search_query_var}{else}q{/if}" type="text" class="search-query" placeholder="{$lang.search}">
			</form>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="span9">
	
			{include file="path.tpl"}
			
			{if $special.is_index_page eq 1}
			<div class="page-header">
			  <h1>{$_settings.logo_text}</h1>
			</div>
			{/if}
			
			{$special.document.panel[0].beforepanel}
			{assign var=loop_center_panel value=1}
			{assign var=show_center_panel value=1}
			{section name=mod_index loop=$modules step=1 start=1}
			{if $_settings.main_block_position lt $loop_center_panel and $show_center_panel eq 1}
			{assign var=show_center_panel value=0}
			{assign var=index value=0}
			{assign var=mod_name value=$modules[0].module}
			{$special.document.block[0].beforeblock}
			{include file="$mod_name.tpl"}
			{$special.document.block[0].afterblock}
			{/if}
			{if $modules[mod_index].panel eq "center"}
			{assign var=index value=$smarty.section.mod_index.index}
			{assign var=mod_name value=$modules[mod_index].module}
			{$special.document.block[mod_index].beforeblock}
			{include file="$mod_name.tpl"}
			{$special.document.block[mod_index].afterblock}
			{assign var=loop_center_panel value=$loop_center_panel+1}
			{/if}
			{/section}
			{if $show_center_panel eq 1}
			{assign var=show_center_panel value=0}
			{assign var=index value=0}
			{assign var=mod_name value=$modules[0].module}
			{$special.document.block[0].beforeblock}
			{include file="$mod_name.tpl"}
			{$special.document.block[0].afterblock}
			{/if}
			{$special.document.panel[0].afterpanel}
	
		</div>
		<div class="span3">
			{$special.document.panel[1].beforepanel}
			{section name=mod_index loop=$modules step=1}
			{if $modules[mod_index].panel eq "1"}
			{assign var=index value=$smarty.section.mod_index.index}
			{assign var=mod_name value=$modules[mod_index].module}
			{$special.document.block[mod_index].beforeblock}
			{include file="$mod_name.tpl"}
			{$special.document.block[mod_index].afterblock}
			{/if}
			{/section}
			{$special.document.panel[1].afterpanel}
		</div>
	</div>
	<div class="row">
		{$special.document.panel[2].beforepanel}
		{section name=mod_index loop=$modules step=1}
		{if $modules[mod_index].panel eq "2"}
		{assign var=index value=$smarty.section.mod_index.index}
		{assign var=mod_name value=$modules[mod_index].module}
		{$special.document.block[mod_index].beforeblock}
		{include file="$mod_name.tpl"}
		{$special.document.block[mod_index].afterblock}
		{/if}
		{/section}
		{$special.document.panel[2].afterpanel}
	</div>
	
	<hr>
	
	<footer>
	  {if $_settings.bottom_menu_id neq ""}{include file="bottommenu.tpl"}{/if}
	  <p>{$_settings.copyright_text}</p>
	  <p>Powered by <a href="http://www.simancms.org" target="_blank">SiMan CMS</a></p>
	</footer>
</div> <!-- /container -->

<script src="themes/bootstrap/js/jquery.js"></script>
<script src="themes/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
{literal}
$('.carousel').carousel({
interval: 2000
})
{/literal}
</script>
{include file="page_footer.tpl"}