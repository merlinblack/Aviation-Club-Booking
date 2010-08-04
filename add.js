

function validate_hour( field )
{
	with( field )
	{
		if( value == null || value == "" || value < 0 || value > 23 )
		{
			var x=document.getElementById("error");
  			x.innerHTML = "<ul><li>Hour must be between 0 and 23</li></ul>";
			return false;
		}
		else
			return true;
	}
}
function validate_minute( field )
{
	with( field )
	{
		if( value == null | value == "" || value < 0 || value > 59 )
		{
			var x=document.getElementById("error");
  			x.innerHTML = "<ul><li>Minutes must be between 0 and 59</li></ul>";
			return false;
		}
		else
			return true;
	}
}
function validate_form( thisform )
{
	with( thisform )
	{
		if( validate_hour( start_hour ) == false )
		{
			start_hour.focus();
			return false;
		}
		if( validate_minute( start_minute ) == false )
		{
			start_minute.focus();
			return false;
		}
		if( validate_hour( end_hour ) == false )
		{
			end_hour.focus();
			return false;
		}
		if( validate_minute( end_minute ) == false )
		{
			end_minute.focus();
			return false;
		}
		if( validate_description( description ) == false )
		{
			description.focus();
			return false;
		}
	}
	return true;
}
function validate_description( field )
{
	if( field.value == null || field.value == "" )
	{
		var x=document.getElementById("error");
  		x.innerHTML = "<ul><li>You must give a description</li></ul>";		
		return false;
	}
	return true;
}
function description_onblur( field )
{
	var x = document.getElementById( 'icon_description' );
	if( validate_description( field ) )
		x.innerHTML = '<img src="images/correct_32x32.png" width="16" height="16">';
	else
		x.innerHTML = '<img src="images/wrong_32x32.png" width="16" height="16" title="You must give a description">';
}
function init()
{
	//document.getElementById( 'start_date' ).onchange = validate_start_date;	// Doesn't work it seems.
	document.getElementById( 'start_hour' ).onblur = validate_start_date;
	document.getElementById( 'start_minute' ).onblur = validate_start_date;
	//document.getElementById( 'end_date' ).onchange = validate_end_date;	// Doesn't work it seems.
	document.getElementById( 'end_hour' ).onblur = validate_end_date;
	document.getElementById( 'end_minute' ).onblur = validate_end_date;
}
function str2date( strdate )
{
	// return new Date( strdate ) works in everything but IE6,7 and 8.  IE sucks.

	var splitdate = strdate.split('-');

	return new Date( splitdate[0], splitdate[1] - 1, splitdate[2] )
}
function validate_start_date()
{
	// Get elements that make up start date.
	var date = document.getElementById( 'start_date' );
	var hour = document.getElementById( 'start_hour' );
	var minute = document.getElementById( 'start_minute' );
	
	if( ! validate_hour( hour ) )
		return;
		
	if( ! validate_minute( minute ) )
		return;
	
	var icon = document.getElementById( 'icon_start_date' );
	icon.innerHTML = '<img src="images/ajax-loader.gif" width="16" height="16">';
	
	// AJAX Query
	var startdate = str2date( date.value );
	startdate.setHours( hour.value );
	startdate.setMinutes( minute.value );
	
	ajax_validate_date( startdate.getTime() / 1000, 'start_date' ); 	
	
	// Push end date out if it is earlier than start date.
	var enddate = str2date( document.getElementById( 'end_date' ).value );
	if( startdate > enddate )
	{
		toggleCalendar( 'end_date' );		
		setValue( 'end_date', date.value );
	}	
}
function validate_end_date()
{
	// Get elements that make up start date.
	var date = document.getElementById( 'end_date' );
	var hour = document.getElementById( 'end_hour' );
	var minute = document.getElementById( 'end_minute' );
	
	if( ! validate_hour( hour ) )
		return;
		
	if( ! validate_minute( minute ) )
		return;
	
	var icon = document.getElementById( 'icon_end_date' );
	icon.innerHTML = '<img src="images/ajax-loader.gif" width="16" height="16">';
	
	// AJAX Query
	var enddate = str2date( date.value );
	enddate.setHours( hour.value );
	enddate.setMinutes( minute.value );
	
	ajax_validate_date( enddate.getTime() / 1000, 'end_date' ); 		
}
