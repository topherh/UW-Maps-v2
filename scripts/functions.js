// clear out the global search input text field
function make_blank() {
	document.form1.q.value = "";
}

$(function() {
    setAutoComplete("searchField", "results", "autocomplete.php?part=");
});
$(function() {
    var tabContainers = $('div.subTabs > div');
    tabContainers.hide().filter(':first').show();

    $('div.subTabs ul.tabNavigation a').click(function () {
    	tabContainers.hide();
    	tabContainers.filter(this.hash).show();
    	$('div.subTabs ul.tabNavigation a').removeClass('selected');
    	$(this).addClass('selected');
    	return false;
    }).filter(':first').click();
});
// function MM_jumpMenu(targ,selObj,restore){ //v3.0
//   eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
//   if (restore) selObj.selectedIndex=0;
// }
$(function() {
    $(".results-label")
        .mouseover(function(){
            $(this)
                .data("origWidth", $(this).css("width"))
                .css("width", "auto");
        })
    $(".results-label option")
        .mouseout(function(){
            $(this).css("width", $(this).data("origWidth"));
        });
});

// wait for the DOM to be loaded and wait for comments
$(document).ready(function(){
    $("#feedbackSubmit").click(function(){                                     
        $(".error").hide();
        var hasError = false;
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        
        var emailVal = $("#email").val();
        if (emailVal == '')
        {
            $("#email").after('<span class="error">Please enter an email address.</span>');
            hasError = true;
        }
        else if (!emailReg.test(emailVal)) 
        {
            $("#email").after('<span class="error">Please enter a valid email address.</span>');
            hasError = true;
        }

        var messageVal = $("#comment").val();
        if (messageVal == '') {
            $("#comment").after('<span class="error">You forgot to enter a comment.</span>');
            hasError = true;
        }
        
        if(hasError == false) 
        {
            var data = new Object();
            data.email = emailVal;
            data.message = messageVal;
            var dataString = $.toJSON(data)
            $(this).hide();
            $("#feedbackForm").append('<img src="/maps/img/loading.gif" alt="Loading" id="loading" />');
            
            $.post("comment.php",
                { data: dataString },
                function(resp)
                {
                    var obj = $.evalJSON(resp); 
                    if (obj == true) 
                    {
                        $("#feedback").slideUp("normal", function()
                        {
                            $("#feedback").before('<h3>Awesome!</h3><p>Thanks for the comment!</p>');                                          
                        });
                    }
                    else
                    {
                        $("#feedback").slideUp("normal", function()
                        {
                            $("#feedback").before('<h3>Fail!</h3><p>Massive problem.</p>'); 
                        });
                    }
                }
            );
        }
        return false;
    });                        
});

/**
 * AutoComplete Field - JavaScript Code
 *
 * This is a sample source code provided by fromvega.
 * Search for the complete article at http://www.fromvega.com
 *
 * Enjoy!
 *
 * @author fromvega
 *
 */

// global variables
var acListTotal   =  0;
var acListCurrent = -1;
var acDelay		  = 500;
var acURL		  = null;
var acSearchId	  = null;
var acResultsId	  = null;
var acSearchField = null;
var acResultsDiv  = null;

function setAutoComplete(field_id, results_id, get_url){

	// initialize vars
	acSearchId  = "#" + field_id;
	acResultsId = "#" + results_id;
	acURL 		= get_url;

	// create the results div
	$("body").append('<div id="' + results_id + '"></div>');

	// register mostly used vars
	acSearchField	= $(acSearchId);
	acResultsDiv	= $(acResultsId);

	// reposition div
	repositionResultsDiv();
	
	// on blur listener
	acSearchField.blur(function(){ setTimeout("clearAutoComplete()", 200) });

	// on key up listener
	acSearchField.keyup(function (e) {

		// get keyCode (window.event is for IE)
		var keyCode = e.keyCode || window.event.keyCode;
		var lastVal = acSearchField.val();

		// check an treat up and down arrows
		if(updownArrow(keyCode)){
			return;
		}

		// check for an ENTER or ESC
		if(keyCode == 13 || keyCode == 27){
			clearAutoComplete();
			return;
		}

		// if is text, call with delay
		setTimeout(function () {autoComplete(lastVal)}, acDelay);
	});
}

// treat the auto-complete action (delayed function)
function autoComplete(lastValue)
{
	// get the field value
	var part = acSearchField.val();

	// if it's empty clear the resuts box and return
	if(part == ''){
		clearAutoComplete();
		return;
	}

	// if it's equal the value from the time of the call, allow
	if(lastValue != part){
		return;
	}

	// get remote data as JSON
	$.getJSON(acURL + part, function(json){

		// get the total of results
		var ansLength = acListTotal = json.length;

		// if there are results populate the results div
		if(ansLength > 0){

			var newData = '';

			// create a div for each result
			for(i=0; i < ansLength; i++) {
				newData += '<div class="unselected">' + json[i] + '</div>';
			}

			// update the results div
			acResultsDiv.html(newData);
			acResultsDiv.css("display","block");
			
			// for all divs in results
			var divs = $(acResultsId + " > div");
		
			// on mouse over clean previous selected and set a new one
			divs.mouseover( function() {
				divs.each(function(){ this.className = "unselected"; });
				this.className = "selected";
			})
		
			// on click copy the result text to the search field and hide
			divs.click( function() {
				acSearchField.val(this.childNodes[0].nodeValue);
				clearAutoComplete();
			});

		} else {
			clearAutoComplete();
		}
	});
}

// clear auto complete box
function clearAutoComplete()
{
	acResultsDiv.html('--No Results--');
	acResultsDiv.css("display","none");
}

// reposition the results div accordingly to the search field
function repositionResultsDiv()
{
	// get the field position
	var sf_pos    = acSearchField.offset();
	var sf_top    = sf_pos.top;
	var sf_left   = sf_pos.left;

	// get the field size
	var sf_height = acSearchField.height();
	var sf_width  = acSearchField.width();

	// apply the css styles - optimized for Firefox
	acResultsDiv.css("position","absolute");
	acResultsDiv.css("left", sf_left - 2);
	acResultsDiv.css("top", sf_top + sf_height + 5);
	acResultsDiv.css("width", sf_width - 2);
}


// treat up and down key strokes defining the next selected element
function updownArrow(keyCode) {
	if(keyCode == 40 || keyCode == 38){

		if(keyCode == 38){ // keyUp
			if(acListCurrent == 0 || acListCurrent == -1){
				acListCurrent = acListTotal-1;
			}else{
				acListCurrent--;
			}
		} else { // keyDown
			if(acListCurrent == acListTotal-1){
				acListCurrent = 0;
			}else {
				acListCurrent++;
			}
		}

		// loop through each result div applying the correct style
		acResultsDiv.children().each(function(i){
			if(i == acListCurrent){
				acSearchField.val(this.childNodes[0].nodeValue);
				this.className = "selected";
			} else {
				this.className = "unselected";
			}
		});

		return true;
	} else {
		// reset
		acListCurrent = -1;
		return false;
	}
}
