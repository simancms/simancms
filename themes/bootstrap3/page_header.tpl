<!DOCTYPE html>
<html lang="en"><head>{$special.document.headstart}{$special.document.headdef}

	<link href="themes/{$special.theme}/css/bootstrap.css" rel="stylesheet">
	<link href="themes/{$special.theme}/stylesheets.css" rel="stylesheet">

	<script src="themes/{$special.theme}/js/jquery.js"></script>
	<script src="themes/{$special.theme}/js/bootstrap.js"></script>
	<script type="text/javascript" src="themes/{$special.theme}/script.js"></script>
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
{config_load file="main.cfg"}
<body class="allbody"{if $special.body_onload neq ""} onload="{$special.body_onload}"{/if}{$special.document.bodymodifier}>{$special.document.bodystart}
{if $_settings.header_static_text neq ""}{$_settings.header_static_text}{/if}

