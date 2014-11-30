<?php
/* %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
 * the purpose of this page is to display a list of poets sorted 
 * 
 * Written By: Robert Erickson robert.erickson@uvm.edu
 * Last updated on: November 20, 2014
 */
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%

    require_once('../bin/myDatabase.php');

    $dbUserName = get_current_user() . '_reader';
    $whichPass = "r"; //flag for which one to use.
    $dbName = strtoupper(get_current_user()) . '_Record';

    $thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);
    
$admin = true;
include "top.php";

print "<section>";
// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// prepare the sql statement
$orderBy = "ORDER BY pmkHouseholdName";

$query  = "SELECT pmkHouseholdName, pmkUsername, fldEmail, fldType, fldPaid, fldLost, fldAmount, fldMonth, pmkBillName ";
$query .= "FROM tblPerson, tblBills, tblHousehold " . $orderBy;

if ($debug)
    print "<p>sql " . $query;

$households = $thisDatabase->select($query);

if ($debug) {
    print "<pre>";
    print_r($households);
    print "</pre>";
}

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// print out the results
print "<ol>\n";

foreach ($households as $householdname) {

    print "<li>";
    if ($admin) {
        print '<a href="update.php?id='.$householdname["pmkBillName"].'">[Edit]</a> ';
    }
    print $householdname['pmkBillName'] . "</li>\n";
}
print "</ol>\n";
print "</section>";
include "footer.php";
?>