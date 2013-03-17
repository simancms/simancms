<!DOCTYPE html>
<html lang="en">
<head>{$special.document.headstart}
	<title>{if $_settings.meta_resource_title_position eq 1 or $_settings.meta_resource_title_position eq 0 and $special.pagetitle eq ""}{$_settings.resource_title}{if $special.pagetitle neq ""}{$_settings.title_delimiter}{/if}{/if}{$special.pagetitle}{if $_settings.meta_resource_title_position eq 2}{if $special.pagetitle neq ""}{$_settings.title_delimiter}{/if}{$_settings.resource_title}{/if}</title>
	<meta content="text/html; charset={$lang.charset}" http-equiv=Content-Type>
	<meta name="description" content="{$special.meta.description}"> 
	<meta name="keywords" content="{$special.meta.keywords}">
	<meta name="GENERATOR" content="SiMan CMS">
	<base href="{if $special.page.scheme neq ""}{$special.page.scheme}{else}http{/if}://{$special.resource_url}">
{if $refresh_url neq ""}
<script type="text/javascript">
{literal}
setTimeout(function() { document.location.href = "{/literal}{$refresh_url}{literal}"; }, 3000)
{/literal}
</script>
{/if}
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

    <link href="themes/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
	{literal}
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	{/literal}
    </style>
    <link href="themes/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="themes/bootstrap/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="themes/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="themes/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="themes/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="themes/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png">

{$_settings.meta_header_text}
{$special.document.headend}</head>
{config_load file="main.cfg"}
<body class="allbody"{if $special.body_onload neq ""} onload="{$special.body_onload}"{/if}{$special.document.bodymodifier}>{$special.document.bodystart}
{if $_settings.header_static_text neq ""}{$_settings.header_static_text}{/if}

