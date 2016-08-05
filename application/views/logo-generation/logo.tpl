<h1>Download AEGEE Logo</h1>
<p>Download your AEGEE logo! For locals with a smaller name the logo should be fine. Locals with a longer name, the download tool may have difficulties generating a nice logo. In that case please contact the PRC.</p>
<p>If you have any issues, question or suggestion, check the <a href="{{ci_url function='site_url' segments="docs/faq"}}">FAQ</a> page or the <a href="{{ci_url function='site_url' segments="docs/inquiries"}}">Inquiries</a> page. Updates of the tool and known bugs can be found on the <a href="{{ci_url function='site_url' segments="docs/change-log"}}">Change log</a> page.</p>

<form id="form-generate-logo" method="POST" action="{{ci_url function='site_url' segments="logo/generate"}}">
	<input type="hidden" name="{{$data.csrf.name}}" value="{{$data.csrf.hash}}"> 
	<input type="hidden" name="token" value="{{$data.csrf.hash}}" id="request">
	<div class="form-field">
		<div class="form-field-title"><label for="form-field-local" id="label-local">Your antenna:<label></div>
		<div class="form-field-element">
			<select name="local" id="form-field-local">
			{{foreach $data.locals as $local}}
				<option value="{{$local->BodyCode}}">{{$local->BodyName}}</option>
			{{foreachelse}}
				<option>No options</option>
			{{/foreach}}
			</select>	
		</div>
	</div>
	<fieldset id="form-fieldset">
		<legend>Options</legend>
		<div class="form-field">
			<div class="form-field-title">
				<label id="label-subtext" for="form-field-subtext">Include subtext</label>
			</div>
			<div class="form-field-element">
				<select name="subtext" id="form-field-subtext">
					<option value="none">--none--</option>
				{{foreach $data.subtexts as $subtext}}
					<option value="{{$subtext->language}}">{{$subtext->language}} - {{$subtext->subtext}}</option> 
				{{foreachelse}}
					<option>No options</option>
				{{/foreach}}
				</select><a class="label-help" title="This phrase will be placed below your logo.">?</a>
			</div>
		</div>
		<div class="form-field">
			<div class="form-field-title">
				<label id="label-format">Formats</label>
			</div>
			<div class="form-field-element">
				<ul>
					<li><label><input type="checkbox" name="format[]" rel="format" value="png" checked> PNG <a class="label-help" title="This image is pixel based and has a transparent background">?</a></label></li>
					<li><label><input type="checkbox" name="format[]" rel="format" value="jpeg" checked> JPEG <a class="label-help" title="For normal usage, a pixel based image.">?</a></label></li>
					<li><label><input type="checkbox" name="format[]" rel="format" value="pdf" > PDF <a class="label-help" title="A vector based image">?</a></label></li>
					<li><label><input type="checkbox" name="format[]" rel="format" value="eps" > EPS <a class="label-help" title="A vector image. You can use this to edit your logo in Illustrator or Inkscape">?</a></label></li>
					<li><label><input type="checkbox" name="format[]" rel="format" value="svg" > SVG <a class="label-help" title="A vector image. This is the source file of your logo.">?</a></label></li>
				</ul>
			</div>
		</div>
		<div class="form-field">
			<div class="form-field-title">
				<label id="label-size">Size</label>
				{{**<span class="caption">Not yet implemented</span>**}}
			</div>
			<div class="form-field-element">
				<ul>
					<li><label><input type="checkbox" name="size[]" rel="size" value="thumb" >Thumbnail - 145x100px <a class="label-help" title="The thumbnail version will not contain a subtext.">?</a></label></li>
					<li><label><input type="checkbox" name="size[]" rel="size" value="small" checked>Small - 600x350px <a class="label-help" title="When you want to inlcude a subtext, the height of your image will increase slightly.">?</a></label></li>
					<li><label><input type="checkbox" name="size[]" rel="size" value="medium" checked>Medium - 1200x700px <a class="label-help" title="When you want to inlcude a subtext, the height of your image will increase slightly.">?</a></label></li>
					<li><label><input type="checkbox" name="size[]" rel="size" value="large">Large - 2400x1400px <a class="label-help" title="When you want to inlcude  a subtext, the height of your image will increase slightly.">?</a></label></li>
				</ul>
			</div>
		</div>
		<div class="form-field">
			<div class="form-field-title">
				<label id="label-colour">Colour</label>
			</div>
			<div class="form-field-element">
				<ul class="swatches">
					<li><label><input type="checkbox" name="colour[]" rel="colour" value="blue" checked> <span class="swatch swatch-blue">Blue</span></label></li>
					<li><label><input type="checkbox" name="colour[]" rel="colour" value="black"> <span class="swatch swatch-black">Black</span></label></li>
					<li><label><input type="checkbox" name="colour[]" rel="colour" value="white"> <span class="swatch swatch-white">White</span></label></li>
				</ul>
			</div>
		</div>
		
		<div class="form-field">
			<div class="form-field-title">
				<label id="label-europelogo">AEGEE-Europe logo</label>
			</div>
			<div class="form-field-element">
				<ul>
					<li><label><input type="radio" name="extra" value="none" checked> None </label></li>
					<li><label><input type="radio" name="extra" value="europe1" > Include basic AEGEE-Europe logos (~3.3MB) <a class="label-help" title="This package only includes blue medium-sized logo's in jpg and png format.">?</a></label></li>
					<li><label><input type="radio" name="extra" value="europe2" > Include extended AEGEE-Europe logos (~31MB) <a class="label-help" title="This package only includes the all blue and black logo's in jpg and png format.">?</a> </label></li>
					<li><label><input type="radio" name="extra" value="europe3" > Include advanced AEGEE-Europe logos (~55MB) <a class="label-help" title="This package only includes the all jpg, png, eps and svg files.">?</a> </label></li>
				</ul>
			</div>
		</div>
	
	</fieldset>	
	<div class="form-field">
		<p>Generating the logos may take up to {{$data.stats.maxExecTimeRounded }} seconds. Please be patient!</p>
			<input type="submit" name="generate" value="Generate & Download" id="button-generate">	
			<a class="button button-cancel" href="{{ci_url function='site_url' segments="logo/cancel/"}}" id="button-cancel">Cancel</a>
			<a class="button button-reset" href="{{ci_url function='site_url' segments="logo/customise"}}" id="button-another">Download another Logo</a>
	</div>
	<div class="form-field form-field-progress">
		<div id="progressbar"></div>
		<div id="progressMessage"></div>
	</div>
	<div>
		<h2>Stats</h2>
		<p>Since {{$data.stats.since}}
		<ul>
		<li>Total number of logo's generated: {{$data.stats.totRequests}}</li>
		<li>Average generation time: {{$data.stats.avExecTime }} seconds</li>
		{{*<li>Total generation time: {{$data.stats.totExecTime }} seconds</li>*}}
		<li>Longest generation time: {{$data.stats.maxExecTime }} seconds</li>
		</ul>
		</p>
	</div>
</form>
<script>
window.maxExecutionTime = {{$data.stats.maxExecTimeRounded }} * 1.5 ;
</script>