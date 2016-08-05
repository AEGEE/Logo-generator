
var OMS = OMS || { 'settings': {}, 'behaviors': {}, 'themes': {}, 'locale': {} };

OMS.verticalTabs = OMS.verticalTabs || {};
OMS.settings.verticalTabs = OMS.settings.verticalTabs || {};
 
OMS.behaviors.verticalTabs = function() {
  if (!$('.vertical-tabs-list').size() && OMS.settings.verticalTabs) {
    var ul = $('<ul class="vertical-tabs-list"></ul>');
    var panes = $('<div class="vertical-tabs-panes"></div>');
		
		var n = 0;
		$.each($('fieldset.tabbed'), function(fieldsetIndex) {

			var el = $('fieldset.tabbed')[fieldsetIndex];
			if(n == 0){
				$('<div class="vertical-tabs clear-block"></div>').insertBefore($(el));
				n++;
			}
			el.id = $(el).attr('id');
			$(el).addClass('vertical-tabs-' + el.id);
			
			el.callback = (OMS.settings.id) ? OMS.settings.id.callback : $(el).attr('id');
			el.name = (OMS.settings.name) ? OMS.settings.id.name : $(el).attr('title');
			el.args = (OMS.settings.args) ? OMS.settings.id.args : [];

      var summary = '', cssClass = 'vertical-tabs-list-' + el.id;
      if (el.callback && OMS.verticalTabs[el.callback]) {
        summary = '<span class="summary">'+ OMS.verticalTabs[el.callback].apply($(el), el.args) +'</span>';
      }
      else {
        cssClass += ' vertical-tabs-nosummary';
      }

      // Add a list item to the vertical tabs list.
      $('<li class="vertical-tab-button"><a href="#' + el.id + '" class="' + cssClass + '"><strong>'+ el.name + '</strong>' + summary +'</a></li>').appendTo(ul)
        .find('a')
        .bind('click', function() {
          $(this).parent().addClass('selected').siblings().removeClass('selected');
          $('.vertical-tabs-' + el.id).show().siblings('.vertical-tabs-pane').hide();
          return false;
      });

      // Find the contents of the fieldset (depending on #collapsible property).
      var fieldset = $('<fieldset></fieldset>');
      var fieldsetContents = $('.vertical-tabs-' + el.id + ' > ol >  *');
      if (fieldsetContents.size()) {
        fieldsetContents.appendTo(fieldset);
      }
      else {
        $('.vertical-tabs-' + el.id).children().appendTo(fieldset);
      }

      // Remove the legend from the fieldset.
      fieldset.children('legend').remove();

      // Add the fieldset contents to the toggled fieldsets.
      fieldset.appendTo(panes)
      .addClass('vertical-tabs-' + el.id)
      .addClass('vertical-tabs-pane')
      .find('input, select, textarea').bind('change', function() {
        if (el.callback && OMS.verticalTabs[el.callback]) {
          $('vertical-tabs-list-' + el.id + ' span.summary').html(OMS.verticalTabs[el.callback].apply($(el), el.args));
        }
      });
    });

		$('fieldset.tabbed').remove();
    $('div.vertical-tabs').html(ul).append(panes);

    // Add an error class to any fieldsets with errors in them.
    $('fieldset.vertical-tabs-pane').each(function(i){
      if ($(this).find('div.form-item .error').size()) {
        $('li.vertical-tab-button').eq(i).addClass('error');
      }
    })

    // Activate the first tab.
    $('fieldset.vertical-tabs-pane').hide();
    $('fieldset.vertical-tabs-pane:first').show();
    $('div.vertical-tabs ul li:first').addClass('first selected');
    $('div.vertical-tabs ul li:last').addClass('last');
    $('div.vertical-tabs').show();
  }
}

OMS.behaviors.verticalTabsReload = function() {
	$.each($('fieldset.tabbed'), function(index) {
		var el = $('fieldset.tabbed')[index];
		id = $(el).attr('id');
		callback = (OMS.settings.id) ? (OMS.settings.id.callback) : $(el).attr('id');
		args = (OMS.settings.args) ? (OMS.settings.id.args) : [];

    if (callback && OMS.verticalTabs[callback]) {
      $('a.vertical-tabs-list-' + id + ' span.summary').html(OMS.verticalTabs[callback].apply($(el), args));
    }
  });
}


OMS.attachBehaviors = function(context) {
  context = context || document;
  // Execute all of them.
  jQuery.each(OMS.behaviors, function(j) {
    this(context);
  });
};

$(document).ready(function() {
    OMS.attachBehaviors(this); 
});
