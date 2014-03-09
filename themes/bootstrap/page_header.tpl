<!DOCTYPE html>
<html lang="en">
<head>{$special.document.headstart}{$special.document.headdef}
	<meta name="GENERATOR" content="SiMan CMS">
	<script src="themes/{$special.theme}/js/jquery.js"></script>
	<script src="themes/{$special.theme}/js/bootstrap.js"></script>
	<script type="text/javascript" src="themes/{$special.theme}/script.js"></script>
	<link href="themes/{$special.theme}/stylesheets.css" type="text/css" rel=stylesheet>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="themes/{$special.theme}/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
	{literal}
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	{/literal}
    </style>
    <link href="themes/{$special.theme}/assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

{$_settings.meta_header_text}
{$special.document.headend}</head>
{config_load file="main.cfg"}
<body class="allbody"{if $special.body_onload neq ""} onload="{$special.body_onload}"{/if}{$special.document.bodymodifier}>{$special.document.bodystart}
{if $_settings.header_static_text neq ""}{$_settings.header_static_text}{/if}

