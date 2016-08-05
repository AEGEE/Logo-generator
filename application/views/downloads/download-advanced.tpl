<h1>Advanced Design Files</h1>
<p>This page contains files that are for more advanced usage. You can download the swatches and symbols for Adobe Illustrator and Photoshop. Also, you can </p>
<h2>Colour Scheme</h2>
<ul>
	<li>Adobe Kuler: <a href="https://kuler.adobe.com/AEGEE-Europe-(RGB)-color-theme-3062745/" target="_AEGEE">Colour scheme</a></li>
	<li>Adobe Illustrator swatches</li>
	<li>Adobe Photoshop swatches</li>
	<li>Adobe swatch exchange</li>
</ul>

<h2>Symbols</h2>
<ul>
	<li>Adobe Illustrator symbols</li>
</ul>

<h2>Web Design - CSS snippets</h2>
<ul>
	<li>Colour bar</li>
	<div class="colorLine" style="width: 100%"></div>
	<pre><code class="css">.colorLine{
	display: block;
	width: 100%;
	height: 10px;
	background: linear-gradient(to right, 
		rgba(201,65,55,1) 0%, rgba(201,65,55,1) 16%, 
		rgba(255,255,255,0) 16%, rgba(255,255,255,0) 16.8%, 
		rgba(168,195,66,1) 16.8%, rgba(168,195,66,1) 32.8%, 
		rgba(255,255,255,0) 32.8%, rgba(255,255,255,0) 33.6%, 
		rgba(145,27,125,1) 33.6%, rgba(145,27,125,1) 49.6%, 
		rgba(255,255,255,0) 49.6%, rgba(255,255,255,0) 50.4%, 
		rgba(234,184,24,1) 50.4%, rgba(234,184,24,1) 66.4%, 
		rgba(255,255,255,0) 66.4%, rgba(255,255,255,0) 67.2%, 
		rgba(20,104,197,1) 67.2%, rgba(20,104,197,1) 83.2%, 
		rgba(255,255,255,0) 83.2%, rgba(255,255,255,0) 84%,
		rgba(145,27,125,1) 84%, rgba(145,27,125,1) 100%); 
}</code></pre>
</ul>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/highlight.min.js"></script>
<script>
$(document).ready(function($){ 
	hljs.configure({
	  tabReplace: '    ', // 4 spaces
						  // … other options aren't changed
	})
	hljs.initHighlighting(); 
})</script>