<?php /* Smarty version Smarty-3.1.14, created on 2016-08-05 12:43:12
         compiled from "/home/other/logo-generator/public_html/application/views/chunks/toplinks.tpl" */ ?>
<?php /*%%SmartyHeaderCode:155056299757a46dc0f0cbf2-51353539%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a240e39c2d0d8b5ce2d6a51d43d3dba3d1537c97' => 
    array (
      0 => '/home/other/logo-generator/public_html/application/views/chunks/toplinks.tpl',
      1 => 1469568502,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '155056299757a46dc0f0cbf2-51353539',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_57a46dc0f16648_44608048',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57a46dc0f16648_44608048')) {function content_57a46dc0f16648_44608048($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ci_url')) include '/home/other/logo-generator/public_html/application/third_party/smarty/customplugins/function.ci_url.php';
?><div id="aegee-top-links">
	<div class="aegee-top-links-wrapper">
		<nav class="aegee-top-links">
			<ul>
				<li id="favicon"><a href="#" title="This website is part of AEGEE.org"><img height=32 src="<?php echo smarty_function_ci_url(array('function'=>'site_url','segments'=>"assets/img/top-links-favicon.png"),$_smarty_tpl);?>
"></a></li>
				<li><a href="http://www.aegee.org">AEGEE.org</a></li>
				<li><span class="descender">&raquo;</span></li>
				<li><a href="http://www.zeus.aegee.org/logo-generator">Logo generator</a></li>
				<li><span class="seperator">|</span></li>
				<li><a target="_AEGEE" href="http://oms.aegee.org">OMS</a></li>
				<li><a target="_AEGEE" href="http://www.aegee.org/forum">Forum</a></li>
				<li><a target="_AEGEE" href="http://www.aegee.org/su">Summer University</a></li>
				<li><a target="_AEGEE" href="http://zeus.aegee.org/statutory">Statutory</a></li>
			</ul>
		</nav>
		
	</div>
</div>

<?php }} ?>