<!DOCTYPE html>
<html lang="en"><head>{$special.document.headstart}
	<title>{if $_settings.meta_resource_title_position eq 1 or $_settings.meta_resource_title_position eq 0 and $special.pagetitle eq ""}{$_settings.resource_title}{if $special.pagetitle neq ""}{$_settings.title_delimiter}{/if}{/if}{$special.pagetitle}{if $_settings.meta_resource_title_position eq 2}{if $special.pagetitle neq ""}{$_settings.title_delimiter}{/if}{$_settings.resource_title}{/if}</title>
	<meta content="text/html; charset={$lang.charset}" http-equiv=Content-Type>
	<meta name="description" content="{$special.meta.description}">
	<meta name="keywords" content="{$special.meta.keywords}">
	<meta name="GENERATOR" content="SiMan CMS">
	<base href="{if $special.page.scheme neq ""}{$special.page.scheme}{else}http{/if}://{$special.resource_url}">

	<link href="themes/{$special.theme}/css/bootstrap.css" rel="stylesheet">

{if $refresh_url neq ""}
	<script type="text/javascript">
			{literal}
					setTimeout(function() { document.location.href = "{/literal}{$refresh_url}{literal}"; }, 3000)
		{/literal}
	</script>
{/if}
	<script src="themes/{$special.theme}/js/jquery.js"></script>
	<script src="themes/{$special.theme}/js/bootstrap.js"></script>
	<script type="text/javascript" src="themes/{$special.theme}/script.js"></script>
{section name=i loop=$special.customjs}
	<script type="text/javascript" src="{$special.customjs[i]}"></script>
{/section}
	<link href="themes/{$special.theme}/stylesheets.css" type="text/css" rel=stylesheet>
{section name=i loop=$special.cssfiles}
	<link href="themes/{$special.theme}/{$special.cssfiles[i]}" type="text/css" rel=stylesheet>
{/section}
{section name=i loop=$special.customcss}
	<link href="{$special.customcss[i]}" type="text/css" rel="stylesheet" />
{/section}
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="themes/{$special.theme}/js/html5shiv.js"></script>
	<script src="themes/{$special.theme}/js/respond.min.js"></script>
	<![endif]-->
{$_settings.meta_header_text}
{$special.document.headend}</head>
<body class="allbody"{if $special.body_onload neq ""} onload="{$special.body_onload}"{/if}{$special.document.bodymodifier}>{$special.document.bodystart}
{if $_settings.header_static_text neq ""}{$_settings.header_static_text}{/if}

