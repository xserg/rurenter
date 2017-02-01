
function unavaildate( pdate, d1, m1, y1, change_day, first_day, jtext){
// this is if the date the user has picked is not available
alert(" The date " + pdate + " is not available, please choose again");
}

function nopriceset( pdate, d1, m1, y1, change_day, first_day, jtext){
// this is if the date the user has picked is not available
alert(" The date " + pdate + " has no price set. Please contact the owner by the enquiry form for more information or choose again");
}

function viewbooking( pdate, d1, m1, y1, change_day, first_day, jtext){
// this is if the date the user has picked is not available
	if (confirm("Do you wish to view this booking: " )){
		document.location = "memberbookingview.asp?" + jtext ;
	}
	
}


function temp_prices(pval)
{
// this sets the price forthe text box for the mouse hover
	document.forms.calendar.temp_price.value = pval;
}

function check_send(){

	// this is to see which type of date selection was used to get the dates....
	// if dates choosen from the drop down menu.... insert into text boxes so that page functions normally
	with(document.forms.calendar){
			req1.value = day1.options[document.forms.calendar.day1.selectedIndex].value  + "/" + month1.options[document.forms.calendar.month1.selectedIndex].value + "/" + year1.options[document.forms.calendar.year1.selectedIndex].value;
			req2.value = day2.options[document.forms.calendar.day2.selectedIndex].value  + "/" + month2.options[document.forms.calendar.month2.selectedIndex].value + "/" + year2.options[document.forms.calendar.year2.selectedIndex].value;
			
			action = "http://www.rentalsystems.com/advert_price.asp?ref=1738&pref=1738&webref=&ck=ck&req1=" + req1.value + "&req2=" + req2.value +"&rag=10821A&rcam=myvillarenters&dscode=" + document.forms.calendar.dscode.value + "&ctype=";
			
			var sdjs = new Date();
			var edjs = new Date();
			sdjs.setFullYear(year1.options[document.forms.calendar.year1.selectedIndex].value, (month1.options[document.forms.calendar.month1.selectedIndex].value - 1), day1.options[document.forms.calendar.day1.selectedIndex].value);
			edjs.setFullYear(year2.options[document.forms.calendar.year2.selectedIndex].value, (month2.options[document.forms.calendar.month2.selectedIndex].value - 1), day2.options[document.forms.calendar.day2.selectedIndex].value);
			var mdjs = new Date();
			var md = "01/05/2012";
			var mda = md.split("/");
			mdjs.setFullYear(mda[2], (mda[1]) - 1, mda[0]);
			//if ((req1.value > "01/05/2012") || (req2.value > "01/05/2012"))
			if ((sdjs > mdjs) || (edjs > mdjs))
			{
				alert("Selected dates are not available, please check your booking dates!");
				return false;
			}
	}	
	
	var people = document.forms.calendar.extra_people.options[document.forms.calendar.extra_people.selectedIndex].value;
	
	if(people == 'noselect') {
	    alert('Please select the number of people');
	}
	else {
			document.forms.calendar.submit();// this then uses which ever action is required
    } 
}

/*

function check_send(){

	// this is to see which type of date selection was used to get the dates....
	// if dates choosen from the drop down menu.... insert into text boxes so that page functions normally
	with(document.forms.calendar){
		req1.value = day1.options[document.forms.calendar.day1.selectedIndex].value  + "/" + month1.options[document.forms.calendar.month1.selectedIndex].value + "/" + year1.options[document.forms.calendar.year1.selectedIndex].value;
		req2.value = day2.options[document.forms.calendar.day2.selectedIndex].value  + "/" + month2.options[document.forms.calendar.month2.selectedIndex].value + "/" + year2.options[document.forms.calendar.year2.selectedIndex].value;
		action = "http://www.rentalsystems.com/advert_price.asp?ref=1738&pref=1738&webref=&ck=ck&req1=" + req1.value + "&req2=" + req2.value +"&rag=10821A&rcam=myvillarenters&dscode=" + document.forms.calendar.dscode.value + "&ctype=";
	    }	
        document.forms.calendar.submit(); // this then uses which ever action is required
        }

*/

function pickdate( pdate, d1, m1, y1, change_day, first_day, jtext){
	 //' this stops the function from producing the error 
//alert(pick);
//pdate is  date in string format
var date_OK
date_OK = false;
var days;
// firstly check to see if the start date is a preferred start day.. if not alert user
with(document.forms.calendar){
		if(date1.value == '' && date2.value == ''){
			date1.value = y1 + "/" + m1 +"/" + d1; //pick;
			req1.value = pdate;
			day1.selectedIndex = parseInt(d1) -1;
			month1.selectedIndex = parseInt(m1) - 1;
			year1.selectedIndex = parseInt(y1) - 2009;}
		else if(date1.value != '' && date2.value ==''){
			date2.value = y1 + "/" + m1 +"/" + d1; //pick;
			req2.value = pdate;
			day2.selectedIndex = parseInt(d1) -1;
			month2.selectedIndex = parseInt(m1) - 1;
			year2.selectedIndex = parseInt(y1) - 2009;
			date_OK = true;
			var t1 = new Date(date1.value);
			var t2 = new Date(date2.value);
			var dt1 = new Date();
			var dt2 = new Date();
			var diff = new Date();
			dt1.setTime(t1.getTime());
			dt2.setTime(t2.getTime());
			diff.setTime((dt2.getTime() - dt1.getTime()) );
			timediff = diff.getTime();
			days = timediff / (1000 * 60 * 60 * 24 );
			//alert(days);
			}
		else if (date1.value != '' && date2.value != ''){
			date1.value = y1 + "/" + m1 +"/" + d1; //pick;
			req1.value = pdate;
			day1.selectedIndex = parseInt(d1) -1;
			month1.selectedIndex = parseInt(m1) - 1;
			year1.selectedIndex =  parseInt(y1) - 2009;
			req2.value = '';
			date2.value = '';}
			
				
		if ( days < 1 && date_OK ){
				
				date_OK = false;
			alert(" Your Start date is greater than your end date, Please choose again");
			req1.value = '';
			date1.value = '';
			day.value = '';
			req2.value = '';
			date2.value = '';
			}	
			
		else if ( change_day != first_day && date1.value != '' && date2.value == ''){
			alert( jtext);
			date1.value = '';
			date2.value = '';
			}
			
			
	
		if (date_OK){
				action = "http://www.rentalsystems.com/?ref=1738&pref=1738&ck=ck&req1="+ req1.value + "&req2=" + req2.value + "&ctype=&rag=10821A&rcam=myvillarenters" ;}

	}// end of with
	
}


function booked_alert(msg){
alert(msg);
}
