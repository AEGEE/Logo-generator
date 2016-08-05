var timer, timer2;
var timeout = false; // milliseconden
var timedOut = false;
var cancelled = false;
var prevStep = 0;
var stepsTotal = 100;
var finished = false;
var curPercentage = 0;
var token;

function setProgress(progress){
	var stepCurrent = progress.status;
	token = progress.token;	
	
	if(stepCurrent <= prevStep){
		// previous step was the same, start timer for timing out the script if it stays on the same step.
		// timeout checker
		if(!timer){
			timeout = window.maxExecutionTime || timeout;
			timer2 = setTimeout(function(){
				checkGenerationTimeout(prevStep)
			}, timeout * 1000);
		}
		
		// don't make the progress bar go backwards..
		stepCurrent = prevStep;
	}
	else{
		clearTimeout(timer2);
		timer2 = false
	}
	
	var percentage = stepCurrent/stepsTotal*100;
	if(curPercentage > percentage){
		percentage = curPercentage
	}
	curPercentage = percentage;
	
	console.log(percentage+'%')
	
	$("#progressMessage").html(progress.message);
	/*$( "#progressbar" ).progressbar({
		value: Math.round(percentage)
	});*/
	$( ".ui-progressbar-value").css({ 'width' : percentage+'%'})
	
	if(stepCurrent == 999){
		console.log('a geberation error occured. What to do?')
		showErrors(3);
		// clear timeouts
		clearTimeout(timer);
		clearTimeout(timer2);
		
	}

	if(percentage < 100 && !timedOut && !cancelled)
	{
		timer = setTimeout(function(){ 
			getProgress(token) }, 1000
		);
	}
	
	prevStep = stepCurrent
}

function getProgress(token)
{
	console.log('get progress')
	
	$.ajax({
		url: 'status?token='+token,
		dataType: "json",
		method: "GET",
		success: function(resp) 
		{
			token = resp.progress.token;
			
			console.log(resp)
			$("#progressMessage").html(resp.message);
		
			if(resp.csrf){
				$("input[name="+resp.csrf.name+"]").val(resp.csrf.hash)
			}
		
			if(resp.progress){
				setProgress(resp.progress);
			}

			// finished generating, download link is presented
			if(resp.downloadLink)
			{
				var downloadLink = resp.downloadLink;
				console.log(downloadLink);
				
				sizeString = '';
				if(resp.downloadSize)
				{
					sizeString = ' ('+resp.downloadSize+'MB) ';
				}
				
				// force progress bar to show 100%
				$( ".ui-progressbar-value").css({ 'width' : '100%'})
				$("#progressMessage").show().html("<p>Your logo is ready for download!</p><p>Click this link if your download does not start within 5 seconds: <a href='"+downloadLink+"' title=''>Download</a> " + sizeString + "</p> ");
				finished = true;
				clearTimeout(timer);
				clearTimeout(timer2);
				$('#button-another').show();
				// add small delay before initiating download
				setTimeout(function(){ 
					resetProgress();
					document.location = downloadLink 
				}, 1000);
				return false;
			}
		},
		error: function(xhr, status, error)
		{
			showErrors(xhr.status)
			return false;
		},
		statusCode: {
			200: function(e){ console.log('success 200') },
			201: function(e){ console.log('success 201') },
			404: function(e){ showErrors(404) },
			412: function(e){ showErrors(412)  }
		}
	});
	
}
/*
function cancel(){
	$.ajax({
		method: 'POST',
		url: 'cancel',
		dataType: "json",
		data: $(this).serialize(),
		
		success: function(resp) 
		{
			console.log(resp)
			if(resp.csrf){
				$("input[name="+resp.csrf.name+"]").val(resp.csrf.hash)
			}

		},
		
		error: function(xhr, status, error)
		{
			console.log(xhr)
			console.log(status)
			console.log(error)
			
			showErrors(xhr.status)
			
			return false;
		}
	});
}
*/

function checkGenerationTimeout()
{
	if (finished == false)
	{
		timedOut = true;
		clearTimeout(timer);
		clearTimeout(timer2);
		showErrors(2);
	}
}

function showErrors(error)
{
	clearTimeout(timer);
	clearTimeout(timer2);
	switch(error)
	{
		case 1:
			message = 'An unknown error occured. Please try again later.';
			break;
		
		case 2:
			message = 'Logo generation timed out. Please try again later';
			break;
		
		case 3:
			message = 'The generator failed unexpectedly. Please contact the administrator with this string ('+token+')';
			break;
		
		case 404:
			message = 'Could not find the page. Please contact the administrator.';
			break;
		
		case 412:
			message = 'Your submitted the form insufficiently. Please check your input.';
			break;
			
		case 452:
			message = 'You cancelled the generation request.';
			break;
			
		default:
			message = 'An unknown error occured.';
			break;
	}	
	
	// reset variables

	resetProgress();
	$('#progressMessage').html(message).addClass('label-error');
	$('#button-another').show();
	$('#button-cancel').hide();
	$('#button-generate').hide();
	var timedOut = false;
	var prevStep = 0;
	var stepsTotal = 0;
}

function resetProgress()
{
	//$('#progressMessage').html('').removeClass('label-error');
	$('#button-cancel').hide();
	$('#progressbar' ).hide();
	$('#progressbar' ).progressbar({
		value: 0
	});
}


$(document).ready(function(){

	$('#progressbar' ).hide();
	$('#button-another').hide();
	$('#button-cancel').hide();
	$("input[name=token]").data( 'sessiontoken', $("input[name=token]").val() )
	
	
	$( "#form-generate-logo" ).on( "change", function( event ) {
		
		timedOut = false;
		
		if($("input[name='format[]']:checked").length == 0)
		{
			$('#label-format').addClass('label-error')
		}
		else
		{
			$('#label-format').removeClass('label-error')
		}
		
		if($("input[name='size[]']:checked").length == 0)
		{
			$('#label-size').addClass('label-error')
		}
		else
		{
			$('#label-size').removeClass('label-error')
		}
		
		if($("input[name='colour[]']:checked").length == 0)
		{
			$('#label-colour').addClass('label-error');
		}
		else
		{
			$('#label-colour').removeClass('label-error');
		}
	});
	
	$('#button-another').on( "click", function( event ) {
		event.preventDefault();
		
		$.ajax({
			method: 'GET',
			url: 'customise',
			dataType: "json",
			//data: $(this).serialize(),
			success: function(resp) 
			{
				if(resp.csrf){
					$("input[name="+resp.csrf.name+"]").val(resp.csrf.hash)
					$("input[name=token]").data('sessiontoken', resp.csrf.hash)
				}
				$('#button-generate').show(); // prop('disabled', true)
				$('#form-field-local').prop('disabled', false);
				$('#form-fieldset').slideDown(200, 'swing');	
				$('#button-cancel').hide();
				$('#button-another').hide();	
				$('#progressMessage').html('').removeClass('label-error');				
			},
			error: function(xhr, status, error)
			{
				showErrors(xhr.status)				
				//document.location = document.location
			}
		})
	})
	
	
	$('#button-cancel').on( "click", function( event ) {
		event.preventDefault();
			
		$.ajax({
			method: 'GET',
			url: 'cancel',
			dataType: "json",
			//data: $(this).serialize(),
			success: function(resp) 
			{
				if(resp.csrf){
					$("input[name="+resp.csrf.name+"]").val(resp.csrf.hash)
					$("input[name=token]").data('sessiontoken', resp.csrf.hash)
				}
				$('#button-generate').show(); // prop('disabled', true)
				$('#form-field-local').prop('enabled', true);
				$('#form-fieldset').slideDown(200, 'swing');	
				$('#button-cancel').hide();
				$('#button-another').hide();	
				//showErrors(452);
			},
			error: function(xhr, status, error)
			{
				showErrors(xhr.status)				
			}
		})
	})
	
	
	$('#form-generate-logo').on( "submit", function( event ) 
	{
		event.preventDefault();
			
		// check input
		if($("input[name='format[]']:checked").length == 0)
		{	
			alert('You did not select any format you want your logo to be. Please select at least 1 option.');
			return false;
		}	
		if($("input[name='size[]']:checked").length == 0)
		{
			alert('You did not select any size you want your logo to be. Please select at least 1 option.');
			return false;
		}
		if($("input[name='colour[]']:checked").length == 0)
		{
			alert('You did not select any color you want your logo to be. Please select at least 1 option.');
			return false;
		}
		
		// add additional number to token
		var d = new Date();
		$("input[name=token]").val( $("input[name=token]").data('sessiontoken')+""+d.getTime() )		
		
		// give some simple feedback that the logo creation is in progress

		$( "#progressbar" ).show();
		$( "#progressbar" ).progressbar({
			value: 1,
			max: 100
		});			
		
		$('#button-generate').hide(); // prop('disabled', true); //
		$('#form-fieldset').slideUp(200, 'swing');	
		$('#button-cancel').show();
	
		//console.log($(this).serialize())
		var token = $("input[name=token]").val()
		$.ajax({
			method: 'POST',
			url: 'generate',
			dataType: "json",
			data: $(this).serialize(),
			success: function(resp) 
			{
				console.log(resp)
				if(resp.csrf){
					$("input[name="+resp.csrf.name+"]").val(resp.csrf.hash)
					$("input[name=token]").val(resp.csrf.hash)
				}
				$('#form-field-local').prop('disabled', true);

				getProgress(token);

			},
			error: function(xhr, status, error)
			{
				showErrors(xhr.status)
				return false;
			}
		});
		
		// asynchronous checking of status while other script is being executed.
		
		return false;
	}); 

})