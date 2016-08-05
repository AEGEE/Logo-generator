<h1>Frequently Asked Questions</h1>
<section class="special-area align-right">
	SEARCH
	<input type="search" placeholder="Search the Questions and Answers" id="faq-search">
	<small id="search-return"></small>
</section>
<section id="faq-list">
<dl>
	<dt>I want a transparent logo, how can I do that?</dt>
	<dd data-tags="ab, transparency, background, png">
		<p>Use the PNG images. They have a transparent background.</p>
	</dd>
</dl>
<dl>
	<dt>I want to customise the logo, can I do that?</dt>
	<dd>
		<p>Only after approval by the Secretary General. The customisation of the logo is restricted to certain guidelines. You can find these in the <a href="{{ci_url function='site_url' segments='docs/visual-identity-manual'}}">Visual Identity Manual</a>.</p>
		<p>These guidelines are created to make all AEGEE logo's look consistent and representative to externals.</p>
	</dd>
</dl>
<dl>
	<dt>The downloads are not working, what can I do?</dt>
	<dd>
		<p>We're sorry that the downloads are not working. Please try to delete your cookies and clear your browser's cache first and try again.</p>
		<p>If that does not work please contact us with the details of the error.</p>
	</dd>
</dl>
<dl>
	<dt>My local is not listed in the download tool?</dt>
	<dd>
		<p>That can happen. Currently the list of locals is managed manually. Therefore we might have missed you or misspelled it. If so please contact the administrator of this website.</p>
	</dd>
</dl>
<dl>
	<dt>My language is not present in the logo download tool?</dt>
	<dd>
		<p>For the <a class="tooltip" title="Subtexts are placed under the logo and are the description of AEGEE in the local language such as European Students' Forum">subtexts</a> (captions) of the logos the available languages are determined by the Comité Directeur. If you would like to add your language, please contact the <a href="{{ci_url function='site_url' segments='docs/inquiries'}}">Comité Directeur</a>.</p>
	</dd>
</dl>
<dl>
	<dt>Which fonts are used for the Logo</dt>
	<dd>
		<p>The logo consists of two different fonts: <em>Helvetica</em> and <em>SF Sports Night Upright</em>. The subtexts are written in <em>Open Sans</em>.</p>
		<p>You can download the fonts on the <a href="{{ci_url function='site_url' segments='downloads/download-elements'}}">downloads</a> page.</p>
	</dd>
</dl>	
<dl>
	<dt>The blue colour of the jpg image is not displayed correctly, how come?</dt>
	<dd>
		<p>The JPG images are optimised for printing. Because printing colours works different than showing colours on a screen the JPG images might look strange.</p> 
		<p>If you want to use the logo digitally we advise you to use the PNG version.</p>
	</dd>
</dl>	
<dl>
	<dt>Which files and programs can I use to edit my logo's?</dt>
	<dd>
		<p>You can edit your logo's using the PDF, EPS or SVG file You can open these files in vector editing programs such as Adobe Illustrator, Corel Draw or Inkscape.</p>
		<p>Not that you may <em>only</em> edit your logo according to the rules of the Visual Identity Manual and <em>only</em> with approval of the Comité Directeur.</p>
	</dd>
</dl>	
<dl>
	<dt>Which size logo should I use for my PR materials?</dt>
	<dd>
		<p>It depends on the type of material what logo will be best to use. In general we suggest you to use the PDF, EPS or SVG versions for items you print at printing houses, like stickers, t-shirts and flags. Then the quality will be much better. Especially for flags we suggest you to use the Large PDF version of the logo.</p>
		<p>For smaller, or less important applications, such as printing a flyer, you can use the PNG or JPEG Logo safely. In case of digital usage the thumbnail, small or medium PNG/JPEG formats will be sufficient.</p>
		<p>Basically you use the following guidelines to determine which format and size to use in printing applications:</p>
		<ul>
			<li><strong>5cm * 3cm and smaller:</strong> Small Logo PNG/JPEG  (<em>flyers</em>)</li>
			<li><strong>10cm * 7cm:</strong> Medium Logo PNG/JPEG (<em>small booklets, A5 paper</em>)</li>
			<li><strong>20cm * 14cm:</strong> Large Logo PNG/JPEG (<em>large booklets, A4 paper</em>)</li>
			<li><strong>Larger than 20cm wide:</strong> Medium Logo PDF/EPS/SVG (<em>A3 posters, flags, t-shirts, stickers</em>)</li>
			<li><strong>Larger than 40cm wide:</strong> Large Logo PDF/EPS/SVG (<em>A2 posters, flags</em>)</li>
		</ul>
	</dd>
</dl>	
</section>
<style>
.highlight{
	background-color: rgba(234,184,24,0.4);
}
</style>
<script>
$.expr[':'].icontains = function(obj, index, meta, stack)
{ 

	if(typeof jQuery(obj).data('tags') !== "undefined")
	{
		console.log( jQuery(obj).data('tags').toLowerCase().indexOf(meta[3].toLowerCase()) )
	}
	return ((obj.textContent || obj.innerText || jQuery(obj).text() ||  obj.data('tags') || obj.data('search') || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0) ? true : false; 
};


$(document).ready(function()
{

	// quick search filtering
	$('#faq-search').on('keyup', function(e)
	{
		var query = $(this).val();
		
		if(query.length > 1)
		{
			$('dl').each(function()
			{
				item = this
				
				if( $(item).find("*:icontains('"+query+"')").length > 0 )
				{
					$(item).show();
					$(item).addClass('search-result');
					$(item).unhighlight()
					$(item).highlight(query)
				}
				else
				{
					$(item).hide();
					$(item).removeClass('search-result');
					$(item).unhighlight()
				}
			})
			$('#search-return').show().text('Results: '+$('dl.search-result').length)
		}
		else
		{
			//show all
			$('dl').show();
			$('.search-result').removeClass('search-result');
			$('dl').unhighlight()
			$('#search-return').hide();
		}
	})
})



jQuery.extend({
    highlight: function (node, re, nodeName, className) {
        if (node.nodeType === 3) {
            var match = node.data.match(re);
            if (match) {
                var highlight = document.createElement(nodeName || 'span');
                highlight.className = className || 'highlight';
                var wordNode = node.splitText(match.index);
                wordNode.splitText(match[0].length);
                var wordClone = wordNode.cloneNode(true);
                highlight.appendChild(wordClone);
                wordNode.parentNode.replaceChild(highlight, wordNode);
                return 1; //skip added node in parent
            }
        } else if ((node.nodeType === 1 && node.childNodes) && // only element nodes that have children
                !/(script|style)/i.test(node.tagName) && // ignore script and style nodes
                !(node.tagName === nodeName.toUpperCase() && node.className === className)) { // skip if already highlighted
            for (var i = 0; i < node.childNodes.length; i++) {
                i += jQuery.highlight(node.childNodes[i], re, nodeName, className);
            }
        }
        return 0;
    }
});

jQuery.fn.unhighlight = function (options) {
    var settings = { className: 'highlight', element: 'span' };
    jQuery.extend(settings, options);

    return this.find(settings.element + "." + settings.className).each(function () {
        var parent = this.parentNode;
        parent.replaceChild(this.firstChild, this);
        parent.normalize();
    }).end();
};

jQuery.fn.highlight = function (words, options) {
    var settings = { className: 'highlight', element: 'span', caseSensitive: false, wordsOnly: false };
    jQuery.extend(settings, options);
    
    if (words.constructor === String) {
        words = [words];
    }
    words = jQuery.grep(words, function(word, i){
      return word != '';
    });
    words = jQuery.map(words, function(word, i) {
      return word.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
    });
    if (words.length == 0) { return this; };

    var flag = settings.caseSensitive ? "" : "i";
    var pattern = "(" + words.join("|") + ")";
    if (settings.wordsOnly) {
        pattern = "\\b" + pattern + "\\b";
    }
    var re = new RegExp(pattern, flag);
    
    return this.each(function () {
        jQuery.highlight(this, re, settings.element, settings.className);
    });
};
</script>