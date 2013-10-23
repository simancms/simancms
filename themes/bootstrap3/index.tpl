{include file="page_header.tpl"}
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{if $special.page.scheme neq ""}{$special.page.scheme}{else}http{/if}://{$special.resource_url}">{$_settings.resource_title}</a>
		</div>
		<div class="navbar-collapse collapse">
			{if $_settings.upper_menu_id neq ""}
			{include file="uppermenu.tpl"}
			{/if}
			<form class="navbar-form navbar-right">
				<div class="form-group">
					<input placeholder="Email" class="form-control" type="text">
				</div>
				<div class="form-group">
					<input placeholder="Password" class="form-control" type="password">
				</div>
				<button type="submit" class="btn btn-success">Sign in</button>
			</form>
		</div><!--/.navbar-collapse -->
	</div>
</div>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
	<div class="container">
		<h1>Hello, world!</h1>
		<p>This is a template for a simple marketing or informational
		   website. It includes a large callout called the hero unit and three
		   supporting pieces of content. Use it as a starting point to create
		   something more unique.</p>
		<p><a class="btn btn-primary btn-lg">Learn more »</a></p>
	</div>
</div>

<div class="container">
	<!-- Example row of columns -->
	<div class="row">
		<div class="col-lg-4">
			<h2>Heading</h2>
			<p>Donec id elit non mi porta gravida at eget metus. Fusce
			   dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut
			   fermentum massa justo sit amet risus. Etiam porta sem malesuada magna
			   mollis euismod. Donec sed odio dui. </p>
			<p><a class="btn btn-default" href="#">View details »</a></p>
		</div>
		<div class="col-lg-4">
			<h2>Heading</h2>
			<p>Donec id elit non mi porta gravida at eget metus. Fusce
			   dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut
			   fermentum massa justo sit amet risus. Etiam porta sem malesuada magna
			   mollis euismod. Donec sed odio dui. </p>
			<p><a class="btn btn-default" href="#">View details »</a></p>
		</div>
		<div class="col-lg-4">
			<h2>Heading</h2>
			<p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis
			   in, egestas eget quam. Vestibulum id ligula porta felis euismod semper.
			   Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh,
			   ut fermentum massa justo sit amet risus.</p>
			<p><a class="btn btn-default" href="#">View details »</a></p>
		</div>
	</div>

	<hr>

	<footer>
		<p>{$_settings.copyright_text}</p>
	</footer>
</div> <!-- /container -->








<table width="100%" cellspacing="0" cellpadding="0" border="0" class="area">
<tr>
    <td colspan="3" class="logobar">
	<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="20%">
			<a href="{if $special.page.scheme neq ""}{$special.page.scheme}{else}http{/if}://{$special.resource_url}"><img src="themes/{$special.theme}/images/logo.gif" border="0" alt="{$_settings.logo_text}"></a>
		</td>
		<td width="80%" valign="top" align="right">

			<form action="index.php" id="staticsearch" method="get">
              {$lang.search}:
                <input name="q" value="{$special.search_text}">
				<input type="hidden" name="m" value="search">
				<input type="submit" value="&gt;&gt;">
            </form>

		</td>
	</tr>
	{if $_settings.upper_menu_id neq ""}
	<tr>
		<td colspan="2">
		{include file="uppermenu.tpl"}
		</td>
	</tr>
	{/if}
	</table>
	</td>
</tr>
<tr>
<td valign="top" width="20%">

<!-- Ліва панель -->
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

	</td>
    <td valign="top" width="60%">

<!-- Середня панель -->
{$special.document.panel[0].beforepanel}
{include file="path.tpl"}
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
	
	</td>
    <td valign="top" width="20%">

<!-- Права панель -->
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

</td>
</tr>
<tr class="bottombar">
    <td class="copyrightbar">{$_settings.copyright_text}</td>
    <td>{if $_settings.bottom_menu_id neq ""}{include file="bottommenu.tpl"}{/if}</td>
    <td><div class="poweredby">Powered by <a href="http://simancms.org/" target="_blank">SiMan CMS</a></div></td>
</tr>
</table>
{include file="page_footer.tpl"}
