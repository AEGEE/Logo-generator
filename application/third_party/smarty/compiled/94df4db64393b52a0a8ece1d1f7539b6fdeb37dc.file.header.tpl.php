<?php /* Smarty version Smarty-3.1.14, created on 2016-08-05 12:43:12
         compiled from "/home/other/logo-generator/public_html/application/views/chunks/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:31712550757a46dc0e86472-98627095%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '94df4db64393b52a0a8ece1d1f7539b6fdeb37dc' => 
    array (
      0 => '/home/other/logo-generator/public_html/application/views/chunks/header.tpl',
      1 => 1469878250,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31712550757a46dc0e86472-98627095',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'global' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_57a46dc0f04ab6_88796343',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57a46dc0f04ab6_88796343')) {function content_57a46dc0f04ab6_88796343($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/other/logo-generator/public_html/application/third_party/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_ci_url')) include '/home/other/logo-generator/public_html/application/third_party/smarty/customplugins/function.ci_url.php';
if (!is_callable('smarty_function_ci_menu')) include '/home/other/logo-generator/public_html/application/third_party/smarty/customplugins/function.ci_menu.php';
?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 ie" lang="en" dir="ltr"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 ie" lang="en" dir="ltr"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 ie" lang="en" dir="ltr"> <![endif]-->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo $_smarty_tpl->tpl_vars['global']->value['base_url'];?>
" />
	<meta property="og:site_name" content="<?php echo $_smarty_tpl->tpl_vars['global']->value['sitename'];?>
" />
	<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['global']->value['keywords'];?>
" />
	<meta name="copyright" content="<?php echo $_smarty_tpl->tpl_vars['global']->value['copyright'];?>
<?php echo smarty_modifier_date_format(time(),"%Y");?>
" />
	<meta name="viewport" content="initial-scale=1">
	<meta name="application-name" content="<?php echo $_smarty_tpl->tpl_vars['global']->value['sitename'];?>
"/> 
	<meta name="msapplication-TileColor" content="#ff6600"/> 
	<meta name="msapplication-TileImage" content="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/img/myaegee-win8-tile.png"),$_smarty_tpl);?>
"/>
	<link rel="shortcut icon" href="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/img/favicon.png"),$_smarty_tpl);?>
" type="image/x-icon" />
	<link href="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/css/style.css"),$_smarty_tpl);?>
" media="all" rel="stylesheet" />
	<link href="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/css/aegee-top-links.css"),$_smarty_tpl);?>
" media="all" rel="stylesheet" />
	<link href="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/css/start/jquery-ui-1.10.3.custom.css"),$_smarty_tpl);?>
" rel="stylesheet">
	<script src="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/js/jquery-1.9.1.js"),$_smarty_tpl);?>
"></script>
	<script src="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/js/jquery-ui-1.10.3.custom.js"),$_smarty_tpl);?>
"></script>
	<script type="text/javascript" src="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/js/logo-form.js"),$_smarty_tpl);?>
"></script>
</head>
<body class="bg-image" id="<?php echo $_smarty_tpl->tpl_vars['page']->value['pageCssIdentifier'];?>
">
<?php echo $_smarty_tpl->getSubTemplate ('chunks/toplinks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<div id="page">
	<div class="wrapper">
		<header>
			<div id="title">
				<img src="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/img/heading-logo-construction.png"),$_smarty_tpl);?>
" class="logo-construction" alt="Logo under construction">
				<h1 id="title">AEGEE Visual Identity</h1>
				<img src="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/img/heading-logo.png"),$_smarty_tpl);?>
" class="title-logo" alt="Logo AEGEE-Europe">
			</div>
			<nav id="main-menu">
				<ul>
					<li><?php echo smarty_function_ci_menu(array('function'=>'site_url','segments'=>"/",'title'=>'Customise your own AEGEE logo','text'=>'Home'),$_smarty_tpl);?>
</li>
					<li><?php echo smarty_function_ci_menu(array('function'=>'site_url','segments'=>"logo",'type'=>"parent",'title'=>'Customise your own AEGEE logo','text'=>'Logo download'),$_smarty_tpl);?>
</li>
					<li><?php echo smarty_function_ci_menu(array('function'=>'site_url','segments'=>"downloads",'type'=>"parent",'title'=>'Customise your own AEGEE logo','text'=>'Other downloads'),$_smarty_tpl);?>
</li>
					<li><?php echo smarty_function_ci_menu(array('function'=>'site_url','segments'=>"page",'type'=>"parent",'title'=>'Customise your own AEGEE logo','text'=>'Other resources'),$_smarty_tpl);?>
</li>
					<li><?php echo smarty_function_ci_menu(array('function'=>'site_url','segments'=>"docs",'type'=>"parent",'title'=>'Information about the Visual Identity of AEGEE-Europe','text'=>'Documentation'),$_smarty_tpl);?>
</li>
				</ul>
			</nav>
		</header>
		<div id="content"><?php }} ?>