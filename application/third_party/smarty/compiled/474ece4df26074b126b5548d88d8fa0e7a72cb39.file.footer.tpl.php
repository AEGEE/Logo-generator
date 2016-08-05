<?php /* Smarty version Smarty-3.1.14, created on 2016-08-05 12:43:12
         compiled from "/home/other/logo-generator/public_html/application/views/chunks/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:79204296357a46dc0f1e625-26371346%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '474ece4df26074b126b5548d88d8fa0e7a72cb39' => 
    array (
      0 => '/home/other/logo-generator/public_html/application/views/chunks/footer.tpl',
      1 => 1469578567,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '79204296357a46dc0f1e625-26371346',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'global' => 0,
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_57a46dc1001629_90832221',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57a46dc1001629_90832221')) {function content_57a46dc1001629_90832221($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/other/logo-generator/public_html/application/third_party/smarty/plugins/modifier.date_format.php';
?>		</div>	
		<div class="colorLine" style="margin-bottom: 3px"></div>
		<footer>
			<aside class="copyright"><?php echo $_smarty_tpl->tpl_vars['global']->value['copyright'];?>
 &copy; <?php echo smarty_modifier_date_format(time(),"%Y");?>
</aside>
			<aside class="footer-widget">
				<ul></ul>
			</aside>
			<aside class="footer-widget"></aside>
			<aside class="footer-widget"></aside>
		</footer>
	</div> <!-- end wrapper -->
</div> <!-- end page -->
<?php if (isset($_smarty_tpl->tpl_vars['data']->value['csrf'])){?>
<script type="text/javascript">
var CFG = {
	url: '<?php echo $_smarty_tpl->tpl_vars['global']->value['base_url'];?>
',
	token: '<?php echo $_smarty_tpl->tpl_vars['data']->value['csrf']['hash'];?>
',
	name: '<?php echo $_smarty_tpl->tpl_vars['data']->value['csrf']['name'];?>
'
};

$(document).ready(function($){
	var jsonObj = {};
	jsonObj[CFG.name] = CFG.token;
	
    $.ajaxSetup({data: jsonObj});
    $(document).ajaxSuccess(function(e,x) {
        var result = $.parseJSON(x.responseText);
        $('input:hidden[name="'+result.name+'"]').val(result.token);
		var jsonObjNew = {};
		jsonObjNew[result.name] = result.token;
		
        $.ajaxSetup({data: jsonObjNew });
    });
});
</script>
<?php }?>	


</body>
</html><?php }} ?>