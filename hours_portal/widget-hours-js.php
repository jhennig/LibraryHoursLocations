<?php
require_once('functions.php');


// NOTE: this widget can accept up to 3 locations/columns for a table view, but only 1 location for text/today/status views
// in addition, the status view will only display library (not reference) hours because of the way that function currently works


// grab submitted values (which locations/hours, what display)
$location1 = isset($_GET['location1']) ? sanitize($_GET['location1']) : 6;
$type1 = isset($_GET['type1']) ? sanitize($_GET['type1']) : 2;
$location2 = isset($_GET['location2']) ? sanitize($_GET['location2']) : '';
$type2 = isset($_GET['type2']) ? sanitize($_GET['type2']) : 3;
$location3 = isset($_GET['location3']) ? sanitize($_GET['location3']) : '';
$type3 = isset($_GET['type3']) ? sanitize($_GET['type3']) : 2;
$display = isset($_GET['display']) ? sanitize($_GET['display']) : 'table';


// send widget as javascript
header('content-type:application/x-javascript');
?>

var hourswidget = "";

<?php
// set current year, week, day, date, time
$currentyear = date('Y');
$currentweek = date('W');
$currentdate = date('Y-m-d');
$currentday = date('l', strtotime($currentdate));
$currenttime = date('H:i:s');

// for testing different dates, times
//$currentyear = '2012';
//$currentweek = '15';
//$currentdate = '2011-12-10';
//$currentday = date('l', strtotime($currentdate));
//$currenttime = '01:00:00';


// find first monday of the year
$firstmon = strtotime("mon jan {$currentyear}");

// weeks offset is always one less than current week
$weeksoffset = $currentweek - 1;

// calculate this week's monday
$thismon = date( 'Y-m-d', strtotime("+{$weeksoffset} week " . date('Y-m-d', $firstmon)) );


// begin widget variable string depending on display
if ($display == 'table') {
?>

hourswidget += '<table class="hours"><caption>This Week ';

<?php
  if (!$location2) {
?>

hourswidget += '<br />';

<?php
  }//closes if
?>

hourswidget += '(<?= date('F j, Y', strtotime($thismon)); ?>)</caption><tbody><tr><th>HOURS</th><th>';

<?php
  // for library hours in column 1
  if ($type1 == 2) {
    
    // exceptions for IKBLC widgets
    switch ($location1) {
    
      case 6:
      $heading = 'IKBLC Building';
      break;
      
      case 7:
      $heading = 'IKBLC Library<br />(AArP/SciEng)';
      break;
      
      case 11:
      $heading = 'Chapman<br />Learning Commons';
      break;
      
      default:
      $heading = 'Open Hours';
      break;
    
    }//closes switch
?>

hourswidget += '<?= $heading; ?>';

<?php
  // for reference hours in column 1
  } else if ($type1 == 3) {
    
    // exception for Koerner
    switch ($location1) {
    
      case 2:
      $heading = 'Reference<br />&amp; Microform';
      break;
      
      default:
      $heading = 'Reference Hours';
      break;
      
    }//closes switch
?>

hourswidget += '<?= $heading; ?>';

<?php    
  }//closes if-elseif  
?>

hourswidget += '</th>'; 

<?php 
  // when a second column has been indicated
  if ($location2) {
?>

hourswidget += '<th>';

<?php
    // for library hours in column 2
    if ($type2 == 2) {
    
      // exceptions for IKBLC widgets
      switch ($location2) {
      
        case 6:
        $heading = 'IKBLC Building';
        break;
        
        case 7:
        $heading = 'IKBLC Library<br />(AArP/SciEng)';
        break;
        
        case 11:
        $heading = 'Chapman<br />Learning Commons';
        break;
        
        default:
        $heading = 'Open Hours';
        break;
      
      }//closes switch
?>  

hourswidget += '<?= $heading; ?>';

<?php      
    // for reference hours in column 2
    } else if ($type2 == 3) {
      
      // exception for Koerner
      switch ($location2) {
      
        case 2:
        $heading = 'Reference<br />&amp; Microform';
        break;
        
        default:
        $heading = 'Reference Hours';
        break;
      
      }//closes switch
?>

hourswidget += '<?= $heading; ?>';

<?php      
    }//closes if-elseif
?>

hourswidget += '</th>';

<?php   
  }//closes if
 
  // when a third column has been indicated
  if ($location3) {
?>

hourswidget += '<th>';

<?php
    // for library hours in column 3
    if ($type3 == 2) {
    
      // exceptions for IKBLC widgets
      switch ($location3) {
      
        case 6:
        $heading = 'IKBLC Building';
        break;
        
        case 7:
        $heading = 'IKBLC Library<br />(AArP/SciEng)';
        break;
        
        case 11:
        $heading = 'Chapman<br />Learning Commons';
        break;
        
        default:
        $heading = 'Open Hours';
        break;
      
      }//closes switch
?>

hourswidget += '<?= $heading; ?>';

<?php    
    // for reference hours in column 3
    } else if ($type3 == 3) {
      
      // exception for Koerner
      switch ($location3) {
      
        case 2:
        $heading = 'Reference<br />&amp; Microform';
        break;
        
        default:
        $heading = 'Reference Hours';
        break;
      
      }//closes switch
?>

hourswidget += '<?= $heading; ?>';

<?php      
    }//closes if-elseif
?>

hourswidget += '</th>';

<?php
  }//closes if
?>

hourswidget += '</tr>';

<?php
  // begin table rows with gray background (= false)
  $alt = false;
  
  // for loop to display day rows
  for ($i = 0; $i < 7; $i++) {
   
    // change day and date with each iteration
    switch ($i) {
      
      case 0:
      $day = 'Monday';
      $ymd = $thismon;
      break;
      
      case 1:
      $day = 'Tuesday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (1*86400)));
      break;
      
      case 2:
      $day = 'Wednesday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (2*86400)));
      break;
      
      case 3:
      $day = 'Thursday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (3*86400)));
      break;
      
      case 4:
      $day = 'Friday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (4*86400)));
      break;
      
      case 5:
      $day = 'Saturday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (5*86400)));
      break;  
      
      case 6:
      $day = 'Sunday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (6*86400)));
      break;
      
    }//closes switch
    
    // grab hours based on date, location and library/reference type
    // returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
    $column1 = getHoursByDate($ymd, $location1, $type1);
    $column2 = getHoursByDate($ymd, $location2, $type2);
    $column3 = getHoursByDate($ymd, $location3, $type3);
   
    // start row display with appropriate tr tag (sets up alternating rows)
    if ($alt == false) {
?>

var row = '<tr class="even';

<?php
      if ($currentday == $day) {
?>

row += ' today';

<?php
      }//closes if
?>

row += '">';

<?php
      $alt = true;
      
    } else {
?>

var row = '<tr class="odd';

<?php
      if ($currentday == $day) {
?>

row += ' today';

<?php
}
?>

row += '">';

<?php
      $alt = false;
      
    }//closes if-else  
?>

row += '<td><?= $day; ?></td>';

<?php    
    // for first column returns
    if ($column1) {
?>

row += '<td>';

<?php
      // display library hours as closed, 24 hrs or a range
      if ($column1[0]['is_closed'] == 1) {
?>

row += 'Closed</td>';

<?php
      } else if ($column1[0]['open_time'] == $column1[0]['close_time'] && $column1[0]['is_closed'] == 0) {
?>

row += 'Open 24 Hrs</td>';

<?php
      } else {
?>

row += '<?= displayTime($column1[0]['open_time']); ?> - <?= displayTime($column1[0]['close_time']); ?></td>';

<?php
      }//closes if-elseif-else
      
    // for no return
    } else {
?>
      
row += 'N/A</td>';

<?php      
    }//closes if-else
    
    // for indicated second location
    if ($location2) {
?>

row += '<td>';

<?php 
      // for second column returns
      if ($column1 && $column2) {
          
        // display library hours as closed, 24 hrs or a range (and reference desk displays as closed if library is closed)
        if ($column2[0]['is_closed'] == 1 || ($location1 == $location2 && $column1[0]['type'] == 2 && $column2[0]['type'] == 3 && $column1[0]['is_closed'] == 1) ) {
?>

row += 'Closed</td>';

<?php
        } else if ($column2[0]['open_time'] == $column2[0]['close_time'] && $column2[0]['is_closed'] == 0) {
?>

row += 'Open 24 Hrs</td>';

<?php
        } else {
?>

row += '<?= displayTime($column2[0]['open_time']); ?> - <?= displayTime($column2[0]['close_time']); ?></td>';

<?php
        }//closes if-elseif-else
      
      // for no return
      } else {
?>

row += 'N/A</td>';

<?php
      }//closes if-else
    
    }//closes if
    
    // for indicated third location
    if ($location3) {
?>

row += '<td>';

<?php
      // for third column returns
      if ($column3) {
        
        // display library hours as closed, 24 hrs or a range
        if ($column3[0]['is_closed'] == 1) {
?>

row += 'Closed</td>';

<?php
        } else if ($column3[0]['open_time'] == $column3[0]['close_time'] && $column3[0]['is_closed'] == 0) {
?>

row += 'Open 24 Hrs</td>';

<?php
        } else {
?>

row += '<?= displayTime($column3[0]['open_time']); ?> - <?= displayTime($column3[0]['close_time']); ?></td>';

<?php
        }//closes if-elseif-else
        
      // for no return 
      } else {
?>

row += 'N/A</td>';

<?php 
      }//closes if-else
      
    }//closes if
?>

row += '</tr>';
    
hourswidget += row;

<?php
  }//closes for
  
  // grab name for monthly hours link
  $URLname = getNameIDs($location1);

  // grab available widget or emergency closure notes for location 1
  $note1 = getWidgetNote($location1);
  $emergencynote1 = getHoursNotes($URLname);
  
  // when a note is included
  if ($note1 || $emergencynote1) {
?>

hourswidget += '<tr class="widget-note"><td colspan="4"><em>Note:</em> &nbsp;';

<?php  
    if ($note1) {
?>

hourswidget += '<?= $note1; ?>';

<?php
    }//closes if
    
    if ($note1 && $emergencynote1) {
?>

hourswidget += '<br /><br />';

<?php
    }//closes if
    
    if ($emergencynote1) {
?>

hourswidget += '<?= $emergencynote1; ?>';

<?php
    }//closes if
?>

hourswidget += '</tr>';

<?php
  }//closes if
?>

hourswidget += '</tbody></table><p><strong>See Also:</strong> <a href="http://hours.library.ubc.ca/#view-<?= $URLname; ?>">Hours Monthly View</a></p>';

<?php
} else if ($display == 'text') {
  
  // put the week's dates in an array
  $ymd = array($thismon, date('Y-m-d', (strtotime($thismon) + (1*86400))), date('Y-m-d', (strtotime($thismon) + (2*86400))), date('Y-m-d', (strtotime($thismon) + (3*86400))), date('Y-m-d', (strtotime($thismon) + (4*86400))), date('Y-m-d', (strtotime($thismon) + (5*86400))), date('Y-m-d', (strtotime($thismon) + (6*86400))) );

  $weeklyhours = array();
  
  // pull in each days hours and add it to a weekly array  
  for ($i = 0; $i < 7; $i++) {
    
    // grab hours based on date and location (library hours)
    // returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
    $dailyhours = getHoursByDate($ymd[$i], $location1, $type1);
    array_push($weeklyhours, $dailyhours);
    
  }//closes for
?>

hourswidget = '<div class="hours-widget"><h2>Hours This Week</h2>';

<?php
  // set up match as false outside the loop
  $match = false;

  // display week's hours, collapsing where appropriate
  for ($i = 0; $i < 7; $i++) {
    
    // variables to compare the next values
    $next_open = isset($weeklyhours[$i+1][0]['open_time']) ? $weeklyhours[$i+1][0]['open_time'] : '0';
    $next_close = isset($weeklyhours[$i+1][0]['close_time']) ? $weeklyhours[$i+1][0]['close_time'] : '0';
    $next_closed = isset($weeklyhours[$i+1][0]['is_closed']) ? $weeklyhours[$i+1][0]['is_closed'] : '0';
    
    // if the next set of hours is the same, set the range start date, change match to true, break the loop
    if ($weeklyhours[$i][0]['open_time'] == $next_open && $weeklyhours[$i][0]['close_time'] == $next_close && $weeklyhours[$i][0]['is_closed'] == $next_closed && $match == false) {
    
      $start_range = date('D', strtotime($ymd[$i]));
      $match = true;
      continue;
    
    // if the next set of hours is the same AGAIN, just skip to the next one
    } else if ($weeklyhours[$i][0]['open_time'] == $next_open && $weeklyhours[$i][0]['close_time'] == $next_close && $weeklyhours[$i][0]['is_closed'] == $next_closed && $match == true) {
    
      continue;
    
    // otherwise, display the hours  
    } else {
?>

hourswidget += '<p><span class="day">';

<?php
      // when a range has been set, display it
      if ($match == true) {
?>

hourswidget += '<?= $start_range; ?>-<?= date('D', strtotime($ymd[$i])); ?>';

<?php
      } else {
?>

hourswidget += '<?= date('l', strtotime($ymd[$i])); ?>';

<?php
      }//closes if-else
?>

hourswidget += '</span> ';

<?php
      // now display the hours
      if ($weeklyhours[$i][0]['is_closed'] == 1) {
?>

hourswidget += 'Closed';

<?php
      } else if ($weeklyhours[$i][0]['is_closed'] == 0 && $weeklyhours[$i][0]['open_time'] == $weeklyhours[$i][0]['close_time']) {
?>

hourswidget += 'Open 24 Hours';

<?php
      } else {
?>

hourswidget += '<?= displayTime($weeklyhours[$i][0]['open_time']); ?>-<?= displayTime($weeklyhours[$i][0]['close_time']); ?>';

<?php
      }//closes if-elseif-else
?>

hourswidget += '</p>';

<?php
    }//closes if-elseif-else
   
    //reset match to false to start the loop over
    $match = false;
    
  }//closes for
?>

hourswidget += '</div>';

<?php
} else if ($display == 'today') {
  
  // grab hours based on date and location
  // returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
  $today = getHoursByDate($currentdate, $location1, $type1);
?>

hourswidget = '<div class="hours-widget"><p><strong>Today\'s Hours:</strong> &nbsp;';

<?php
  // if hours returned
  if ($today) {
    
    // display library hours as closed, 24 hrs or a range
    if ($today[0]['is_closed'] == 1) {
?>

hourswidget += 'Closed All Day</p>';

<?php
    } else if ($today[0]['open_time'] == $today[0]['close_time'] && $today[0]['is_closed'] == 0) {
?>

hourswidget += 'Open 24 Hrs</p>';

<?php
    } else {
?>

hourswidget += '<?= displayTime($today[0]['open_time']); ?> - <?= displayTime($today[0]['close_time']); ?></p>';

<?php
    }//closes if-elseif-else
    
  // if no return
  } else {
?>

hourswidget += 'N/A';

<?php
  }//closes if-else
?>

hourswidget += '</div>';

<?php
} else if ($display == 'status') {
  
  // grab status based on date, time and location (library hours only)
  $status = displayCurrentStatus($currentdate, $currenttime, $location1);
?>

hourswidget = '<div class="hours-widget"><p><strong><?= $status; ?></strong></p></div>';

<?php
} else {
?>

hourswidget = '<div class="hours-widget"><p>Sorry, no hours available for this view.</p></div>';

<?php
}//closes if-elseif-else
?>

if (typeof jQuery != 'undefined') {  

<?php
  // add table styles with jQuery
  if ($display == 'table') {
?>

document.write(hourswidget);

jQuery('.hours').css({ 'background-color' : '#fff', 'border' : '1px solid #ddd', 'border-spacing' : '1px', 'margin-bottom' : '0', 'max-width' : '98%' });
jQuery('.hours caption').css({ 'font-size' : '125%', 'line-height' : '100%', 'margin-bottom' : '8px', 'margin-top' : '0', 'font-weight' : 'bold', 'text-align' : 'center', 'background-color' : '#fff' });
jQuery('.hours th').css({ 'font-size' : '100%', 'background-color' : '#eee', 'text-align' : 'left', 'border' : '1px solid #ddd', 'text-transform' : 'capitalize' });
jQuery('.hours td, .hours th').css({ 'border-bottom' : '1px solid #ddd', 'letter-spacing' : '0', 'padding' : '3px 5px' });
jQuery('.hours tr.even td').css('background', 'none');
jQuery('.hours tr.odd').css('background-color', '#efefef');
jQuery('.hours tr.today').css('background-color', '#ffffbb');
jQuery('.widget-note').css({ 'font-size' : '85%', 'background-color' : '#efefef' });
jQuery('.hours + p').css('margin-top', '8px');

<?php
  // add text styles with jQuery, write into div (if it exits)
  } else if ($display == 'text') {
  
    // grab name for div id
    $URLname = getNameIDs($location1);
?>

// required to accommodate Learning Commons fancy box js
if (document.getElementById('<?= $URLname; ?>')) {
  document.getElementById('<?= $URLname; ?>').innerHTML=hourswidget;
} else {
  document.write(hourswidget);
}//closes if-else

jQuery('.hours-widget').css('margin', '10px 0');
jQuery('.hours-widget h2').css({ 'font-size' : '120%', 'line-height' : '110%', 'margin-bottom' : '15px' });
jQuery('.hours-widget p').css('margin', '4px 0');
jQuery('.hours-widget p .day').css({ 'display' : 'inline-block', 'width' : '90px' });

<?php
  // add status styles with jQuery
  } else if ($display == 'status') {
?>

document.write(hourswidget);

jQuery('.hours-widget').css('margin', '10px 0');
jQuery('.hours-widget .open, .hours-widget .closed').css({ 'font-variant' : 'small-caps', 'font-size' : '120%' });

<?php
  // add today styles with jQuery
  } else if ($display == 'today') {
?>

document.write(hourswidget);

jQuery('.hours-widget').css('margin', '10px 0');

<?php
  }// closes if-elseif
?>

} else {

document.write(hourswidget);

}//closes if-else