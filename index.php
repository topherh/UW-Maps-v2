<?php
$loc = $_GET['l'];
$cat = $_GET['c'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> 
    <title>University of Washington Campus Map</title>
   
    <link href="css/UWInfoWindow.css" type="text/css" rel="Stylesheet" media="screen" />
    <link href="css/main.css" rel="stylesheet" type="text/css" />
 	
 	<!-- Framework CSS -->
	<link rel="stylesheet" href="http://www.washington.edu/common/css/screen.css" type="text/css" media="screen, projection" />
	    
	<!--Header-->
	<link href="http://www.washington.edu/common/css/reset.css" rel="stylesheet" type="text/css" />
	<link href="http://www.washington.edu/common/css/footer.css" rel="stylesheet" type="text/css" />
	<link href="http://www.washington.edu/common/css/typography.css" rel="stylesheet" type="text/css" />    
	<link href="http://www.washington.edu/common/css/header.css" rel="stylesheet" type="text/css" />
	<link href="http://www.washington.edu/common/css/secondary.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript">// clear out the global search input text field
	    function make_blank() {if(document.uwglobalsearch.q.value == "Search the UW") {document.uwglobalsearch.q.value = "";}}
	</script>
	
	<!-- The line below starts the conditional comment -->
	<!--[if IE]>
	      <style type="text/css">
	       body {behavior: url(/common/scripts/csshover.htc);}
	      </style>
	     <![endif]-->
	<!-- This ends the conditional comment -->
 	
    <!-- Google Includes -->
<?php
    if ($_SERVER['HTTP_HOST']=='www.washington.edu')
        echo '    <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAELDzYTvQ326NllQs81d5BxS1qEFRkHRRSgcRbuO1-H7lKO0hixRwj-08ry7DfRTDKTuGnYtuKETchg"></script>';
    else
        echo '    <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAELDzYTvQ326NllQs81d5BxQQSftKFH6IMJjAAUN3JhTwf9BKSRTXJUD0__rg8Fw1JjQZ02n_Y6pqaQ"></script>';
?>
    <script type="text/javascript">
        google.load("maps", "2");
        google.load("jquery", "1.3.2");
    </script>
    <!-- Google Includes -->
    
    <!-- Shared JS code -->
    <script type="text/javascript" src="scripts/jquery.dimensions.pack.js"></script>
    <script type="text/javascript" src="scripts/jquery.json-min.js"></script>
    <script type="text/javascript" src="scripts/functions.js"></script>
    <script type="text/javascript" src="UWMap.js"></script>

    <script type="text/javascript">
    //<![CDATA[
    var cmap;
    
<?php
    if ($cat)
    {
        echo "    var loc = '$loc';";
        echo "    var loccat = '$cat';";
    }
    else if (($loc) && ($cat == null))
    {
        echo "    var loc = '$loc';";
        echo "    var loccat = 'building';";
    }
    else if (($cat) && ($loc== null))
    {
        echo "    var loc = null;";
        echo "    var loccat = '$loc';";
    }
    else
    {
        echo '    var loc = null;';
        echo '    var loccat = null;';
    }
?>

    function OnLoad()
    {
        if (GBrowserIsCompatible())
        {
            cmap = new UWCampusMap();

            GDownloadUrl("markers.xml", function(doc)
            {
                var xmlDoc = GXml.parse(doc);
                cmap.ulocset.load(xmlDoc);
            });

            if (loc)
            {
                cmap.overlay();
                setTimeout('cmap.ulocset.search(loccat,loc,\'code\')', 2000);
            }
            else if (loccat)
            {
                cmap.overlay();
                setTimeout('cmap.ulocset.show(loccat)', 2000);
            }
            else
            {
                cmap.center(16);
            }

            var campusmap = this.campusmap;
            GEvent.addListener(cmap.map, 'click', function(campusmap, point)
            {
                cmap.ulocset.locate(point);
            });
        }
        else
        {
            alert("Sorry, the Google Maps API is not compatible with this browser");
        }
    }    
    
    // onclick event used for the categories displayed on page
    // == a checkbox has been clicked ==
    function boxclick(box,c)
    {
        if (box.checked)
            cmap.ulocset.show(c);
        else
            cmap.ulocset.hide(c);
    }
    // Start the loading process
    window.onload = function()
    {
         OnLoad();
    }
    //]]>
    // Start the loading process
    window.unload = function()
    {
        map2=null;
        GUnload();
    }
    //]]>
    </script>

<link rel="stylesheet" href="http://depts.washington.edu/uweb/inc/css/print.css" type="text/css" media="print" />

</head>
<body>
<!-- Google Analytics -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9574772-1");
pageTracker._trackPageview();
} catch(err) {}
</script> 
<!-- Google Analytics -->

<div class="wheader patchYes colorGold">
  <div id="autoMargin">
   <div class="wlogoSmall">
    <div class="logoAbsolute"><a id="wlogoLink" href="http://www.washington.edu/">University of Washington</a></div>
   </div>
   <div id="wtext">
    <ul>
     <li><a href="http://www.washington.edu/">UW Home</a></li>
     <li><span class="border"><a href="http://www.washington.edu/home/directories.html">Directories</a></span></li>
     <li><span class="border"><a href="http://www.washington.edu/discover/visit/uw-events">Calendar</a></span></li>
     <li><span class="border"><a href="http://www.lib.washington.edu/">Libraries</a></span></li>
     <li><span class="border"><a href="http://www.washington.edu/maps/">Maps</a></span></li>
     <li><span class="border margRight"><a href="http://myuw.washington.edu/">My UW</a></span></li>
     <li><a href="http://www.uwb.edu/">UW Bothell</a></li>
     <li><span class="border"><a href="http://www.tacoma.washington.edu/">UW Tacoma</a></span></li>
    </ul>
   </div>
  </div>
 </div>
<div style="background: url(img/image/bg_portal.gif) repeat-x scroll 0 0 transparent;">
  <div id="header">
   <div id="wsearch">
    <form action="http://www.google.com/cse" id="searchbox_001967960132951597331:04hcho0_drk" name="uwglobalsearch">
     <div class="wfield">
      <input type="hidden" value="001967960132951597331:04hcho0_drk" name="cx" />
      <input type="hidden" value="FORID:0" name="cof" />
      <input type="text" class="wTextInput" value="Search the UW" onclick="make_blank();" title="Search the UW" name="q" />
     </div>
     <input type="submit" value="Go" name="sa" class="formbutton" />
    </form>
   </div>
   <span id="uwLogo"><a href="http://www.washington.edu/">University of Washington</a></span>
   <p class="tagline"><a href="http://www.washington.edu/discovery/washingtonway/"><span class="taglineGold">Discover what's next.</span> It's the Washington Way.</a></p>
   <ul id="navg">
    <li class="mainNavLinkLeft">
     <div class="mainNavLinkRight">
      <h4><a class="mainNavLinkNotch" href="http://www.washington.edu/discover/">Discover the UW</a></h4>
      <br class="clear" />
      <div class="text">
       <div class="mainNavBG">
        <ul class="mainNavLinks">
         <li><a href="http://www.washington.edu/discover/">About</a></li>
         <li><a href="http://www.washington.edu/discover/academics/departments">Academic Departments</a></li>
	 <li><a href="http://www.washington.edu/discover/academics">Colleges and Schools</a></li>
	 <li><a href="http://www.washington.edu/diversity/">Diversity</a></li>
	 <li><a href="http://www.washington.edu/discover/educationalexcellence">Educational Excellence</a></li>
	 <li><a href="http://www.gohuskies.com/">Husky Sports</a></li>
	 <li><a href="http://www.washington.edu/discover/leadership">Leadership</a></li>
	 <li><a href="http://www.washington.edu/discover/news">News Central</a></li>
	 <li><a href="http://depts.washington.edu/mediarel/galleries/">Photo Galleries</a></li>
	 <li><a href="http://www.washington.edu/research/">Research at the UW</a></li>
	 <li><a href="http://www.washington.edu/discover/visionvalues">Vision &amp; Values</a></li>
	 <li><a href="http://www.washington.edu/discover/visit">Visit the UW</a></li>
	 <li><a href="http://www.washington.edu/discover/sustainability">Spotlight on Sustainability</a></li>
	 <li><a href="http://www.washington.edu/discover/healthylives">Spotlight on Healthy Lives</a></li>
	 <li><a href="http://www.washington.edu/discover/globalcitizens">Spotlight on Global Citizens</a></li>
	 <li><a href="http://www.washington.edu/discover/innovation">Spotlight on Innovation</a></li>
        </ul>
        <div class="mainNavBlurb">
         <p>
          <a href="http://www.washington.edu/discover"><img src="http://depts.washington.edu/uweb/inc/img/full/nav_discover.jpg" width="200" height="120" alt="Rainier Vista" /></a>
          <br />
          Founded in 1861, the University of Washington is one of the oldest state-supported institutions of higher education on the West Coast and is one of the preeminent research universities in the world. <a href="http://www.washington.edu/discover" class="more-link">Learn more</a>
         </p>
        </div>
        <br class="clear" />
        <br class="clear" />
       </div>
      </div>
     </div>
    </li>
    <li class="mainNavLinkLeft">
     <div class="mainNavLinkRight">
      <h4><a class="mainNavLinkNotch" href="http://www.washington.edu/students/">Current Students</a></h4>
      <br class="clear" />
	  <div class="text">
	   <div class="mainNavBG">
	    <ul class="mainNavLinks">
	     <li><a href="http://www.washington.edu/students/">Student Guide</a></li>
	     <li><a href="http://www.washington.edu/uaa/">Undergraduate Learning</a></li>
	     <li><a href="http://www.washington.edu/provost/studentlife/">Student Life</a></li>
             <li><a href="http://depts.washington.edu/omad/">Diversity Resources</a></li>
	     <li><a href="http://www.washington.edu/uaa/gateway/advising/majors/majoff.php">Choosing a Major</a></li>
	     <li><a href="http://www.washington.edu/uaa/advising/">Advising</a></li>
	     <li><a href="http://www.washington.edu/students/reg/calendar.html">Academic Calendar</a></li>
	     <li><a href="http://www.washington.edu/students/timeschd/">Time Schedule</a></li>
	     <li><a href="http://f2.washington.edu/fm/sfs/tuition">Tuition, Fees</a></li>
	     <li><a href="http://www.washington.edu/students/osfa/">Financial Aid</a></li>
	     <li><a href="http://www.washington.edu/students/reg/regelig.html">Registration Info</a></li>
	     <li><a href="http://careers.washington.edu/">Career Center</a></li>
	     <li><a href="http://hfs.washington.edu/dining/">Dining</a></li>
	     <li><a href="http://www.lib.washington.edu/">Libraries</a></li>
	     <li><a href="http://www.washington.edu/itconnect/forstudents.html">Computing / IT Connect</a></li>
	     <li><a href="http://myuw.washington.edu/">MyUW</a></li>
             <li><a href="http://alpine.washington.edu/">Alpine / Email</a></li>
	    </ul>
	    <div class="mainNavBlurb">
		 <p>
		  <a href="http://www.washington.edu/provost/studentlife/"><img src="http://depts.washington.edu/uweb/inc/img/full/nav_current_students.jpg" width="200" height="120" alt="Student writing" /></a>
		  <br />
		  The UW is committed to improving the student experience. Plans currently are under way to remodel the Husky Union Building, expand the Ethnic Cultural Center and remodel the Hall Health Primary Care Center. Learn more about <a href="http://www.washington.edu/provost/studentlife/" class="more-link">Student Life</a>
		 </p>
	    </div>
	    <br class="clear" />
	    <br class="clear" />
	   </div>
      </div>
     </div>
    </li>
	<li class="mainNavLinkLeft">
	 <div class="mainNavLinkRight">
	  <h4><a class="mainNavLinkNotch" href="http://admit.washington.edu/">Future Students</a></h4>
	  <br class="clear" />
      <div class="text">
	   <div class="mainNavBG">
	    <ul class="mainNavLinks">
	     <li><a href="/discover/admissions">Undergraduate Admissions</a></li>
	     <li><a href="http://www.washington.edu/uaa/gateway/advising/majors/majoff.php">Undergraduate Majors</a></li>
	     <li><a href="http://www.washington.edu/students/crscat/">Course Descriptions</a></li>
	     <li><a href="http://admit.washington.edu/Requirements/Transfer/Plan/CreditPolicies">Transfer Credit Policies</a></li>
         <li><a href="http://www.outreach.washington.edu/conted/">Continuing Education</a></li>
	     <li><a href="http://www.grad.washington.edu/admissions/programs-degrees.shtml">Graduate School</a></li>
	     <li><a href="http://www.washington.edu/provost/studentlife/">Student Life</a></li>
	     <li><a href="http://f2.washington.edu/fm/sfs/tuition">Tuition, Fees</a></li>
	     <li><a href="http://www.washington.edu/students/osfa/">Financial Aid</a></li>
	     <li><a href="http://www.hfs.washington.edu/housing/">Student Housing</a></li>
         <li><a href="http://hfs.washington.edu/dining/">Dining</a></li>
         <li><a href="http://admit.washington.edu/Visit/GuidedTour">Campus Tours</a></li>
	    </ul>
	    <div class="mainNavBlurb">
	     <p>
	      <a href="http://www.washington.edu/discover/educationalexcellence"><img src="http://depts.washington.edu/uweb/inc/img/full/nav_future_students.jpg" width="200" height="133" alt="UW Band" /></a>
	      <br />
	      Exceptional learning opportunities are around every corner. Our students have gone to the moon. Mapped the human genome. Broken the sound barrier. Created vaccines. Negotiated peace. What amazing things will UW grads do next? <a href="http://www.washington.edu/educationalexcellence" class="more-link">Read more</a>
	     </p>
	    </div>
		<br class="clear" />
		<br class="clear" />
	   </div>
	  </div>
	 </div>
	</li>
	<li class="mainNavLinkLeft">
	 <div class="mainNavLinkRight">
	  <h4><a class="mainNavLinkNotch" href="http://www.washington.edu/facultystaff/">Faculty &amp; Staff</a></h4>
	  <br class="clear" />
      <div class="text">
	   <div class="mainNavBG">
	    <ul class="mainNavLinks">
	     <li><a href="http://f2.washington.edu/fm/payroll/payroll/ESS">Employee Self Service</a></li>
	     <li><a href="http://www.washington.edu/admin/hr/">Human Resources</a></li>
	     <li><a href="http://www.washington.edu/admin/" >Administrative Gateway</a></li>
	     <li><a href="http://www.washington.edu/admin/hr/benefits/">Benefits &amp; Work/Life</a></li>
	     <li><a href="http://uw.edu/jobs/">Jobs</a></li>
	     <li><a href="http://www.washington.edu/safecampus/">SafeCampus</a></li>
	     <li><a href="http://www.washington.edu/discover/leadership">Administration</a></li>
	     <li><a href="http://www.washington.edu/faculty/facsen/">Faculty Senate</a></li>
	     <li><a href="http://www.washington.edu/research/">Research at the UW</a></li>
	     <li><a href="http://www.washington.edu/teaching/">Teaching at the UW</a></li>
	     <li><a href="http://www.washington.edu/admin/acadpers/">Academic HR</a></li>
	     <li><a href="http://depts.washington.edu/psoweb/">Professional Staff Organization</a></li>
	     <li><a href="http://www.lib.washington.edu/">Libraries</a></li>
	     <li><a href="http://www.washington.edu/itconnect/">Computing / IT Connect</a></li>
	     <li><a href="http://myuw.washington.edu/">MyUW</a></li>
	     <li><a href="http://alpine.washington.edu/">Alpine / Email</a></li>
	    </ul>
		<div class="mainNavBlurb">
		 <p>
		  <a href="http://www.washington.edu/discover/visionvalues"><img src="http://depts.washington.edu/uweb/inc/img/full/nav_faculty_staff.jpg" width="200" height="120" alt="Faculty/Staff photo" /></a>
		  <br />
		  The University of Washington recruits the best, most diverse and innovative faculty and staff from around the world, encouraging a vibrant intellectual community for our students. We promote access to excellence and strive to inspire through education. <a href="http://www.washington.edu/discover/visionvalues" class="more-link">Vision &amp; Values</a>
		 </p>
		</div>
		<br class="clear" />
		<br class="clear" />
	   </div>
      </div>
     </div>
	</li>
	<li class="mainNavLinkLeft">
	 <div class="mainNavLinkRight">
	  <h4><a class="mainNavLinkNotch" href="http://www.washington.edu/alumni/">Alumni</a></h4>
	  <br class="clear" />
	  <div class="text">
	   <div class="mainNavBG">
	    <ul class="mainNavLinks">
	     <li><a href="http://www.washington.edu/alumni/meet/">Connect with other Alumni</a></li>
	     <li><a href="http://www.washington.edu/alumni/events/">Alumni Events</a></li>
	     <li><a href="http://www.washington.edu/alumni/services/index.html">Alumni Services</a></li>
	     <li><a href="http://www.washington.edu/alumni/careers/">Networking and Careers</a></li>
	     <li><a href="http://www.washington.edu/alumni/act/">Volunteer Opportunities</a></li>
	     <li><a href="http://www.washington.edu/alumni/tours/">UW Alumni Tours</a></li>
	     <li><a href="http://www.washington.edu/alumni/learn/">Lifelong Learning</a></li>
	     <li><a href="http://www.washington.edu/alumni/membership/">UWAA Membership</a></li>
	     <li><a href="http://www.washington.edu/alumni/meet/facebook.html">UWAA on Facebook</a></li>
	     <li><a href="http://www.washington.edu/alumni/columns/">Columns Magazine</a></li>
	     <li><a href="http://www.washington.edu/alumni/viewpoints/">Viewpoints Magazine</a></li>
	    </ul>
		<div class="mainNavBlurb">
		 <p>
		  <a href="http://www.washington.edu/alumni/meet/groups/happyhours.html"><img src="http://depts.washington.edu/uweb/inc/img/full/nav_alumni.jpg" width="210" height="96" alt="Alumni graphic" /></a>
		  <br />
		  No matter where you are, Husky Happy Hours are a great way to plug into the University of Washington's strong network of alumni. Connect with UW grads in a casual setting and meet fellow alumni in your area. <a href="http://www.washington.edu/alumni/meet/groups/happyhours.html" class="more-link">Details</a>
		 </p>
		</div>
		<br class="clear" />
		<br class="clear" />
	   </div>
	  </div>
	 </div>
	</li>
	<li class="mainNavLinkLeft">
     <div class="mainNavLinkRight">
      <h4><a class="mainNavLinkNotch" href="http://www.washington.edu/nwneighbors/">NW Neighbors</a></h4><br class="clear" />
      <div class="text">
       <div class="mainNavBG">
	    <ul class="mainNavLinks">
	     <li><a href="http://www.washington.edu/community/">UW in the Neighborhood</a></li>
	     <li><a href="http://www.meany.org/tickets/index.aspx">Arts UW Ticket Office</a></li>
         <li><a href="http://www.washington.edu/burkemuseum/">Burke Museum Visitor Info</a></li>
	     <li><a href="http://www.henryart.org">Henry Art Gallery  Visitor Info</a></li>
	     <li><a href="http://ev12.evenue.net/cgi-bin/ncommerce3/ExecMacro/evenue/ev69/se/Main.d2w/report?linkID=uwash">Husky Sports Ticket Office</a></li>
	     <li><a href="http://www.washington.edu/provost/specialprograms/UUF.html">Using UW Resources</a></li>
	     <li><a href="http://www.lib.washington.edu/services/borrow/visitor.html">UW Libraries Visitor Info</a></li>
	     <li><a href="http://uwmedicine.washington.edu/Patient-Care/Locations/UW-Neighborhood-Clinics/Pages/default.aspx">UW Medicine Neighborhood Clinics</a></li>
         <li><a href="http://www.udistrictchamber.org/">University District</a></li>
         <li><a href="http://www.cityofseattle.net/">City of Seattle</a></li>
	     <li><a href="http://www.visitseattle.org/visitors/">Seattle Tourism</a></li>
	     <li><a href="http://www.experiencewa.com/">The Evergreen State</a></li>
	    </ul>
	    <div class="mainNavBlurb">
	     <p>
	      <a href="http://www.washington.edu/discover/visit/huskycentral"><img src="http://depts.washington.edu/uweb/inc/img/full/nav_nw_neighbors.jpg" width="200" height="133" alt="Husky Central storefront" /></a>
          <br />
          Visit Husky Central in downtown Seattle. It's a one-stop location for "everything Husky." <a href="http://www.washington.edu/discover/visit/huskycentral" class="more-link">Store info</a>
         </p>
        </div>
        <br class="clear" />
        <br class="clear" />
	   </div>
	  </div>
     </div>
    </li>
    <li class="medicineLogo"><a href="http://uwmedicine.washington.edu/">UW Medicine</a></li>
   </ul>
  </div>

<div id="rounded">

    <div class="top"><span></span></div>

    <div id="entire">
        <div id="nav">
            <br />
            <div class="headlineCampus">UW Campus Buildings</div>
        
   	  <div id="dotted">
                <div class="subTabs">
                    <ul class="tabNavigation">
                        <li><a href="#search" id="searchTab" onclick="pageTracker._trackPageview('/maps/index-searchtab');">Search</a></li>
                        <li><a href="#browse" id="browseTab" onclick="pageTracker._trackPageview('/maps/index-browsetab');">Browse</a></li>
                    </ul>
                    <br style="clear:both" />
                    <div id="search">
                        <input name="searchField" type="text" id="searchField" />
                        <input id="searchgo" value="Go" type="submit" onclick="cmap.ulocset.search('building',document.getElementById('searchField').value);pageTracker._trackPageview('/maps/index-search');" />
                    </div>
                    <div id="browse">
                        <form id="browseform">
                            <select name="buildingList" size="1" onchange="cmap.ulocset.search('building',this.value,'code');pageTracker._trackPageview('/maps/index-browse');" class="results-label" id="buildingList">
                                <option value="" selected="selected">Select a building...</option>
<?php
    // Grab our categories XML document and prepare for parsing
    $doc = new DOMDocument();
    $doc->load( 'buildings.xml' );

    $markers = $doc->getElementsByTagName( "marker" );

    // We are only searching for the name by looking through all the results
    for ($x=0; $x<$markers->length; $x++)
    {
        $code = $markers->item($x)->getAttribute('code');
        $name = $markers->item($x)->getAttribute('name');
        echo "    <option value=\"$code\">$name ($code)</option>";
    }
?>
                            </select>
                        </form>
                    </div>
                </div>	
            <p><span id="search-error" class="srch-error"></span></p>
    
                <div class="headlineNoteworthy">Noteworthy Locations</div>            
                               
                <ul>
                    <li><a id="fComputing" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="computingbox" onclick="boxclick(this,'computing');pageTracker._trackPageview('/maps/index-computingbox');" />Computer Labs</label></a></li> 
                    <li><a id="fFood" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="foodbox" onclick="boxclick(this,'food');pageTracker._trackPageview('/maps/index-foodbox');" />Food</label></a></li>
                    <li><a id="fGatehouse" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="gatehousebox" onclick="boxclick(this,'gatehouse');pageTracker._trackPageview('/maps/index-gatehousebox');" />Gatehouses</label></a></li>
                    <li><a id="fParking" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="parkingbox" onclick="boxclick(this,'parking');pageTracker._trackPageview('/maps/index-parkingbox');" />Parking Lots</label></a></li>  
                    <li><a id="fLandmarks" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="landmarksbox" onclick="boxclick(this,'landmarks');pageTracker._trackPageview('/maps/index-landmarksbox');" />Landmarks</label></a></li>  
                    <li><a id="fLibrary" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="librarybox" onclick="boxclick(this,'library');pageTracker._trackPageview('/maps/index-librarybox');" />Libraries</label></a></li>  
                    <li><a id="fVisitors" class="forms" href="#"><label><input name="locbox" class="checky" type="checkbox" id="visitorsbox"  onclick="boxclick(this,'visitors');pageTracker._trackPageview('/maps/index-visitorsbox');" />Visitors Center</label></a></li> 
                </ul>
                  
                <h3 class="reset"><a onclick="cmap.reset();pageTracker._trackPageview('/maps/index-reset');" href="#">RESET MAP</a></h3>
            </div>
            <br />
    
            <div id="feedback">
                <form id="feedbackForm" action="/maps/" method="post"> 
                    <label for="email"><span class="feedback">Your Email: </span></label><br /><input class="feedback-in" type="text" id="email" name="email" /> <br />
                    <label for="comment"><span class="feedback">Comments: </span></label><br /><textarea class="feedback-in" id="comment" name="comment"></textarea> 
                    <input id="feedbackSubmit" type="submit" value="Send Comments &raquo;" /> 
                </form>
            </div>
            <div><input id="leave-feedback" name="leave-feedback" value="Leave Feedback" type="button" /></div>
        </div>
           
        <div id="map"></div>
    
        <div id="foot"></div>
    
    </div>
    
    <div class="bottom"><span></span></div>
</div>
</div>
<div id="footer">
    <span class="footLinks">
            <ul>
            <li>Prospective Students</li>
             <li><a href="http://admit.washington.edu/Visit/GuidedTour" onclick="javascript:pageTracker._trackPageview('/maps_guidedtour'); ">Schedule a Guided <br />Campus Tour</a> </li>
             
             
             <li class="remove">Other Campuses</li>
            <li><a href="http://www.uwb.edu/admin/services/transportation/map.xhtml" onclick="javascript:pageTracker._trackPageview('/maps_bothell'); ">Bothell</a> | <a href="http://www.tacoma.washington.edu/campus_map/" onclick="javascript:pageTracker._trackPageview('/maps_tacoma'); ">Tacoma</a></li>
             
  
            </ul>
    </span>
    <span class="footLinks">
        <ul>
            <li>Commuter Resources</li>
            <li><a href="http://www.washington.edu/commuterservices/get_to_uw/maps_directions/index.php" onclick="javascript:pageTracker._trackPageview('/maps_gettingToUW'); ">Getting to the UW</a> </li>
            <li><a href="http://www.washington.edu/commuterservices/parking/index.php" onclick="javascript:pageTracker._trackPageview('/maps_parking'); ">Parking at the UW</a></li> 
            <li><a href="http://www.washington.edu/facilities/transportation/uwshuttles/" onclick="javascript:pageTracker._trackPageview('/maps_shuttle'); ">UW Shuttle Service</a></li>
            <li><a href="http://www.onebusaway.org" onclick="javascript:pageTracker._trackPageview('/maps_onebusaway'); ">One Bus Away</a></li>
        </ul>
    </span>
    <span class="footLinks">
        <ul>
        	<li>Other Maps</li>
  
            <li><a href="http://www.washington.edu/home/maps/" onclick="javascript:pageTracker._trackPageview('/maps_static'); ">Static Campus Map</a></li>            
            <!-- <li><a href="http://flatline.cs.washington.edu/CAMPS/">Campus Walking Directions</a></li> -->
            <li><a href="/home/maps/campusmappg.pdf" onclick="javascript:pageTracker._trackPageview('/maps_printmap'); ">Printable Campus Map</a></li>
			<li><a href="http://www.smokefreeuw.washington.edu/" onclick="javascript:pageTracker._trackPageview('/maps_smokinglocations');">Smoking Locations</a></li>
            <li><a href="/admin/ada/" onclick="javascript:pageTracker._trackPageview('/maps_ada'); ">Disabilities Access Guide</a></li>	
            <li><a href="http://depts.washington.edu/deptgh/docs/healthsciencesmap.pdf" onclick="javascript:pageTracker._trackPageview('/maps_healthsciences'); ">Health Sciences</a></li>     
            <li><a href="http://uwmedicine.washington.edu/Patient-Care/Locations/UWMC/Campus/Getting-to-UW-Medical-Center/Pages/default.aspx" onclick="javascript:pageTracker._trackPageview('/maps_medicalcenter'); ">UW Medical Center</a></li>
            
        </ul>
    </span>
    <span class="footLinks">
        <ul>
        	
            <li>Photography</li>
             <li><a href="http://depts.washington.edu/mediarel/galleries/" onclick="javascript:pageTracker._trackPageview('/maps_campusgallery'); ">Campus Scenes Gallery</a> </li>
             <li><a href="http://uwnews.org/uweek/communityphotos.aspx" onclick="javascript:pageTracker._trackPageview('/maps_communityphotos'); ">Community Photos</a> </li>
             <li><a href="mailto:uweb@uw.edu" onclick="javascript:pageTracker._trackPageview('/maps_email_an_image'); ">Submit a Seattle Campus<br />Building Image</a></li>
            
        </ul>
    </span>
    
    
</div>

<div id="footerMain">
  <div id="footerLeft">    	
		<ul>
   		  <li class="centerText"><a href="http://www.washington.edu/">&#169; <?php echo date('Y'); ?> UNIVERSITY OF WASHINGTON</a></li>  
        </ul>
  </div>    
    <div id="footerRight">  
    	 <ul>  	
  		   <li class="centerText"><a href="http://www.seattle.gov/">SEATTLE, WASHINGTON</a></li>
         </ul>   
  </div>    
  	<div id="footerCenter">
        <ul>
          <li><a href="http://www.washington.edu/home/siteinfo/form">Contact Us</a></li>
          <li class="footerLinkBorder"><a href="http://www.washington.edu/jobs">Jobs</a></li>
          <li class="footerLinkBorder"><a href="http://myuw.washington.edu/">My UW</a></li>
          <li class="footerLinkBorder"><a href="http://www.washington.edu/admin/rules/wac/rulesindex.html">Rules Docket</a></li>
        </ul>
  	</div>
</div>



<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-10899051-1");
pageTracker._trackPageview();
} catch(err) {}</script>


</body>
</html>
