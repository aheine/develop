<?php

/* this example creates a list box from our database.
 * Four step process

  Create your database object using the appropriate database username
  Define your query. In this example we open the file that contains the query.
  Execute the query
  Prepare output and loop through array

 */
//initialize value

include "top.php";
?>
<section>
    <?php
$householdname = "";

// Step one: generally code is in top.php
require_once('../bin/myDatabase.php');

$dbUserName = get_current_user() . '_reader';
$whichPass = "r"; //flag for which one to use.
$dbName = strtoupper(get_current_user()) . '_Record';

$thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);

// Step Two: code can be in initialize variables or where step four needs to be
$query  = "SELECT DISTINCT pmkHouseholdName ";
$query .= "FROM tblHousehold ";
$query .= "ORDER BY pmkHouseholdName";


// Step Three: code can be in initialize variables or where step four needs to be
// $buildings is an associative array
$householdname = $thisDatabase->select($query);

// Step Four: prepare output two methods, only do one of them
/* html looks like this if we were to do this manually (shortened to three 
  buildings

  <label for="lstBuildings">Building
  <select id="lstBuildings"
  name="lstBuildings"
  tabindex="300" >

  <option value="AIKEN">AIKEN</option>
  <option value="KALKIN">KALKIN</option>
  <option value="VOTEY" selected>VOTEY</option>

  </select></label>


  Here is how to code it */

// coded to store output in a variable, this example i use an array
// in the form i build a message to be mailed so the variable is
// $message, in both cases output is stored before printing
/* same thing just not in an array

  $message  = '<label for="lstBuildings">Building"';
  $message .= '<select id="lstBuildings" ';
  $message .= '        name="lstBuildings"';
  $message .= '        tabindex="300" >';

 */

$output = array();
$output[] = '<h2>Values stored in array and then printed</h2>';
$output[] = '<form action="display.php" method="get">';
$output[] = '<label for="lstHouseholdName">Household ';
$output[] = '<select id="lstHouseholdName" ';
$output[] = '        name="lstHouseholdName" ';
$output[] = '        tabindex="300" >';


foreach ($householdname as $row) {

    $output[] = '<option ';
    if ($building == $row["pmkHouseholdName"])
        $output[] = ' selected ';

    $output[] = 'value="' . $row["pmkHouseholdName"] . '">' . $row["pmkHouseholdName"];

    $output[] = '</option>';
}

$output[] = '</select></label>';

print join("\n", $output);  // this prints each line as a separate  line in html

print '<input type="hidden" id="hidHouseholdName" name="hidHouseholdName" value="<?php print $lstHouseholdName;?>"> 
    <input type="submit" id="btnSubmit" name="btnSubmit" value="Select" tabindex="991" class="button">';


print '</form>';

?>
<section>