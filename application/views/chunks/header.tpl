<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 ie" lang="en" dir="ltr"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 ie" lang="en" dir="ltr"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 ie" lang="en" dir="ltr"> <![endif]-->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="{{$global.base_url}}" />
	<meta property="og:site_name" content="{{$global.sitename}}" />
	<meta name="keywords" content="{{$global.keywords}}" />
	<meta name="copyright" content="{{$global.copyright}}{{$smarty.now|date_format:"%Y"}}" />
	<meta name="viewport" content="initial-scale=1">
	<meta name="application-name" content="{{$global.sitename}}"/> 
	<meta name="msapplication-TileColor" content="#ff6600"/> 
	<meta name="msapplication-TileImage" content="{{ci_url function='site_url' segments="assets/img/myaegee-win8-tile.png"}}"/>
	<link rel="shortcut icon" href="{{ci_url function='site_url' segments="assets/img/favicon.png"}}" type="image/x-icon" />
	<link rel="author" href="{{ci_url function='site_url' segments="humans.txt"}}" />
	<link href="{{ci_url function='site_url' segments="assets/css/style.css"}}" media="all" rel="stylesheet" />
	<link href="{{ci_url function='site_url' segments="assets/css/aegee-top-links.css"}}" media="all" rel="stylesheet" />
	<link href="{{ci_url function='site_url' segments="assets/css/start/jquery-ui-1.10.3.custom.css"}}" rel="stylesheet">
	<script src="{{ci_url function='site_url' segments="assets/js/jquery-1.9.1.js"}}"></script>
	<script src="{{ci_url function='site_url' segments="assets/js/jquery-ui-1.10.3.custom.js"}}"></script>
	<script type="text/javascript" src="{{ci_url function='site_url' segments="assets/js/logo-form.js"}}"></script>
</head>
<body class="bg-image" id="{{$page.pageCssIdentifier}}">
{{include file='chunks/toplinks.tpl'}}
<div id="page">
	<div class="wrapper">
		<header>
			<div id="title">
				<img src="{{ci_url function='site_url' segments="assets/img/heading-logo-construction.png"}}" class="logo-construction" alt="Logo under construction">
				<h1 id="title">AEGEE Visual Identity</h1>
				<img src="{{ci_url function='site_url' segments="assets/img/heading-logo.png"}}" class="title-logo" alt="Logo AEGEE-Europe">
			</div>
			<nav id="main-menu">
				<ul>
					<li>{{ci_menu function='site_url' segments="/" title='Customise your own AEGEE logo' text='Home'}}</li>
					<li>{{ci_menu function='site_url' segments="logo" type="parent" title='Customise your own AEGEE logo' text='Logo download'}}</li>
					<li>{{ci_menu function='site_url' segments="downloads" type="parent" title='Customise your own AEGEE logo' text='Other downloads'}}</li>
					<li>{{ci_menu function='site_url' segments="page" type="parent" title='Customise your own AEGEE logo' text='Other resources'}}</li>
					<li>{{ci_menu function='site_url' segments="docs" type="parent" title='Information about the Visual Identity of AEGEE-Europe' text='Documentation'}}</li>
				</ul>
			</nav>
		</header>
		<div id="content">