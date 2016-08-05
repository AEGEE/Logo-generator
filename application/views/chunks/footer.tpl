		</div>	
		<div class="colorLine" style="margin-bottom: 3px"></div>
		<footer>
			<aside class="copyright">{{$global.copyright}} &copy; {{$smarty.now|date_format:"%Y"}}</aside>
			<aside class="footer-widget">
				<ul></ul>
			</aside>
			<aside class="footer-widget"></aside>
			<aside class="footer-widget"></aside>
		</footer>
	</div> <!-- end wrapper -->
</div> <!-- end page -->
{{if isset($data.csrf)}}
<script type="text/javascript">
var CFG = {
	url: '{{$global.base_url}}',
	token: '{{$data.csrf.hash}}',
	name: '{{$data.csrf.name}}'
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
{{/if}}	

{{** debug **}}
</body>
</html>