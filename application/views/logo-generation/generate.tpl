{{if $data.code == 404}}
	<h1>An error has occured.</h1>
	<p>{{$data.message}}</p>
	<p>Please try <a href="{{ci_url function='site_url' segments="logo/cancel"}}">again</a>.</p>
{{else}}
	{{if isset($data.downloadLink)}}
		<h1>Ready</h1>
		<p>Your logos have been generated and the download link is ready: <a href="{{$data.downloadLink}}">Download now!</a> {{if isset($data.downloadSize)}}({{$data.downloadSize}} MB){{/if}}.</p>
		<p><a class="button button-reset" href="{{ci_url function='site_url' segments="logo/cancel"}}" id="button-another">Download another Logo</a></p>
	{{else}}
		<meta http-equiv="refresh" content="2">
		<h1>Generating...</h1>
		<p>Your logo's are being generated.</p>
		<p>Allow this page to refresh itself until your download link is ready.</p>
		<p><a class="button button-reset" href="{{ci_url function='site_url' segments="logo/cancel"}}" id="button-cancel">Cancel & Return</a></p>
	{{/if}}
{{/if}}
