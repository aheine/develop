<?php
/* the purpose of this page is to display a form to allow a person to register
 * the form will be sticky meaning if there is a mistake the data previously 
 * entered will be displayed again. Once a form is submitted (to this same page)
 * we first sanitize our data by replacing html codes with the html character.
 * then we check to see if the data is valid. if data is valid enter the data 
 * into the table and we send and dispplay a confirmation email message. 
 * 
 * if the data is incorrect we flag the errors.
 * 
 * Written By: Robert Erickson robert.erickson@uvm.edu
 * Last updated on: October 17, 2014
 * 
 * 
  -- --------------------------------------------------------
  --
  -- Table structure for table `tblRegister`
  --

  CREATE TABLE IF NOT EXISTS `tblRegister` (
  `pkRegisterId` int(11) NOT NULL AUTO_INCREMENT,
  `fldEmail` varchar(65) DEFAULT NULL,
  `fldDateJoined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fldConfirmed` tinyint(1) NOT NULL DEFAULT '0',
  `fldApproved` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pkRegisterId`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

 * I am using a surrogate key for demonstration, 
 * email would make a good primary key as well which would prevent someone
 * from entering an email address in more than one record.
 */

include "top.php";


require_once('../bin/myDatabase.php');

$dbUserName = get_current_user() . '_writer';
$whichPass = "w"; //flag for which one to use.
$dbName = strtoupper(get_current_user()) . '_Record';

$thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);


$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
//
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;
if (isset($_GET["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}
if ($debug)
    print "<p>DEBUG MODE IS ON</p>";


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1b Security
//
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1c form variables
//
// Initialize variables one for each form element
// in the order they appear on the form
$email = "";
$householdname = "";
$username = "";
$month = "";
$type = "electricity";
$amount = "";
$paid = "yes";
$lost = "no";
$billname = "";
$cash = "";
$credit = "";
$check = "";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$emailERROR = false;
$usernameERROR = false;
$amountERROR = false;
$householdnameERROR = false;
$billnameERROR = false;


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();

// used for building email message to be sent and displayed
$mailed = false;
$messageA = "";
$messageB = "";
$messageC = "";

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
    if($debug) print 'form was submited';
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2a Security
//
//    if (!securityCheck(true)) {
//        $msg = "<p>Sorry you cannot access this page. ";
//        $msg.= "Security breach detected and reported</p>";
//        die($msg);
//    }

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2b Sanitize (clean) data
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.
$email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
$username = htmlentities($_POST["txtUsername"], ENT_QUOTES, "UTF-8");
$householdname = htmlentities($_POST["txtHouseholdName"], ENT_QUOTES, "UTF-8");
$month = htmlentities($_POST["lstMonth"], ENT_QUOTES, "UTF-8");
$type = htmlentities($_POST["radType"], ENT_QUOTES, "UTF-8");
$amount = htmlentities($_POST["txtAmount"], ENT_QUOTES, "UTF-8");
$paid = htmlentities($_POST["radPaid"], ENT_QUOTES, "UTF-8");
$lost = htmlentities($_POST["radLost"], ENT_QUOTES, "UTF-8");
$billname = htmlentities($_POST["txtBillName"], ENT_QUOTES, "UTF-8");
$cash = htmlentities($_POST["chkCash"], ENT_QUOTES, "UTF-8");
$credit = htmlentities($_POST["chkCredit"], ENT_QUOTES, "UTF-8");
$check = htmlentities($_POST["chkCheck"], ENT_QUOTES, "UTF-8");
        



    if(isset($_POST["chkCash"])) {
        $stowe  = true;
    }else{
        $stowe  = false;
    }
    
    if(isset($_POST["chkCredit"])) {
        $sugarbush  = true;
    }else{
        $sugarbush  = false;
    }
    
        if(isset($_POST["chkCheck"])) {
        $madriver  = true;
    }else{
        $madriver  = false;
    }


if($debug) print 'Email: ' . $email;
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2c Validation
//
// Validation section. Check each value for possible errors, empty or
// not what we expect. You will need an IF block for each element you will
// check (see above section 1c and 1d). The if blocks should also be in the
// order that the elements appear on your form so that the error messages
// will be in the order they appear. errorMsg will be displayed on the form
// see section 3b. The error flag ($emailERROR) will be used in section 3c.

    if ($email == "") {
        if($debug) print 'email is blank';
        $errorMsg[] = "Please enter your email address";
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = "Your email address appears to be incorrect.";
        $emailERROR = true;}
    if ($username == "") {
        if($debug) print 'Username is blank';
        $errorMsg[] = "Please enter your username";
        $usernameERROR = true;
    } elseif (!verifyAlphaNum($username)){
        $errorMsg[] = "Your username must be only letters and numbers";
        $usernameERROR = true;
    }
    
    if ($householdname == "") {
        if($debug) print 'Household name is blank';
        $errorMsg[] = "Please enter your household name";
        $usernameERROR = true;
    } elseif (!verifyAlphaNum($householdname)){
        $errorMsg[] = "Your householdname must be only letters and numbers";
        $usernameERROR = true;
    }
    
        if ($amount == "") {
        if($debug) print 'Amount is blank';
        $errorMsg[] = "Please enter the amount";
        $amountERROR = true;
    } elseif (!verifyAlphaNum($amount)){
        $errorMsg[] = "Your username must be only letters and numbers";
        $amountERROR = true;
    }
    if ($billname == "") {
        if($debug) print 'Bill name is blank';
        $errorMsg[] = "Please enter your bill name";
        $billnameERROR = true;
    } elseif (!verifyAlphaNum($billname)){
        $errorMsg[] = "Your billname must be only letters and numbers";
        $billnameERROR = true;
    }


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2d Process Form - Passed Validation
//
// Process for when the form passes validation (the errorMsg array is empty)
//
    if (!$errorMsg) {
        if ($debug)
            print "<p>Form is valid</p>";
        

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2e Save Data
        //
   
        

        $primaryKey = "";
        $dataEntered = false;
        try {
           
            $thisDatabase->db->beginTransaction();
            $query = 'INSERT INTO tblPerson (pmkUsername, fldEmail, fnkHouseholdName) VALUES (?, ?, ?)';

            $data = array( $username, $email, $householdname); 
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
            $results = $thisDatabase->insert($query, $data);
            
            $query1 = 'INSERT INTO tblHousehold (pmkHouseholdName) VALUES (?)';
            $data1 = array($householdname);
            $results1 = $thisDatabase->insert($query1, $data1);
            
            $query2 = 'INSERT INTO tblBills (fldType, fldPaid, fldLost, fldAmount,fldMonth, fnkHouseholdName, fnkUsername, pmkBillName, fldCash, fldCredit, fldCheck) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $data2 = array($type, $paid, $lost, $amount, $month, $householdname, $username, $billname, $cash, $credit, $check);
            $results2 = $thisDatabase->insert($query2, $data2);

            //$primaryKey = $thisDatabase->lastInsert();
            
            if ($debug){
                print_r($results);
            }


// all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();
            //$dataEntered = true;
            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabase->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accpeting your data please contact us directly.";
        }
        // If the transaction was successful, give success message
        if ($dataEntered) {
            if ($debug)
                print "<p>data entered now prepare keys ";
            //#################################################################
            // create a key value for confirmation
//
//            $query = "SELECT fldDateJoined FROM tblRegister WHERE pkRegisterId=" . $primaryKey;
//            $results = $thisDatabase->select($query);

//            $dateSubmitted = $results[0]["fldDateJoined"];
//
//            $key1 = sha1($dateSubmitted);
//            $key2 = $primaryKey;
//
//            if ($debug)
//                print "<p>key 1: " . $key1;
//            if ($debug)
//                print "<p>key 2: " . $key2;


            //#################################################################
            //
//            //Put forms information into a variable to print on the screen
//            //

            $messageA = '<h2>Household expense record has been processed and updated.</h2>';

            $messageB = "<p>We have recorded your expense data and have stored it for you</p>";

            $messageC .= "<p>A notification has been sent to the following people:   " . $email . "</p>";

            //##############################################################
            //
            // email the form's information
            //
            $to = $email; // the person who filled out the form
            $cc = "";
            $bcc = "";
            $from = "Household Expense Manager <noreply@yoursite.com>";
            $subject = "Household expense record has been processed and updated";

           
            $mailed = sendMail($to, $cc, $bcc, $from, $subject, $messageA . $messageB . $messageC);
        } //data entered  
    } // end form is valid
    if($debug) print 'finished form is valid';
} // ends if form was submitted.
//#############################################################################
//
// SECTION 3 Display Form
//
?>
<section id="main">
    <?php
//####################################
//
// SECTION 3a.
//
//
//
//
// If its the first time coming to the form or there are errors we are going
// to display the form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        if($debug) print 'Entered if';
        
        print "<h1>Your Request has ";
        if (!$mailed) {
            print "not ";
        }
        print "been processed</h1>";
        print "<p>A copy of this message has ";
        if (!$mailed) {
            print "not ";
        }
        print "been sent";
        print "to: " . $email . "</p>";
        print $messageA . $messageC;
    } else {
        
    if($debug) print 'Entered else';
//####################################
//
// SECTION 3b Error Messages
//
// display any error messages before we print out the form
        if ($errorMsg) {
            print '<div id="errors">';
            print "<ol>\n";
            foreach ($errorMsg as $err) {
                print "<li>" . $err . "</li>\n";
            }
            print "</ol>\n";
            print '</div>';
        }
//####################################
//
// SECTION 3c html Form
//
        /* Display the HTML form. note that the action is to this same page. $phpSelf
          is defined in top.php
          NOTE the line:
          value="<?php print $email; ?>
          this makes the form sticky by displaying either the initial default value (line 35)
          or the value they typed in (line 84)
          NOTE this line:
          <?php if($emailERROR) print 'class="mistake"'; ?>
          this prints out a css class so that we can highlight the background etc. to
          make it stand out that a mistake happened here.
         */
        ?>
<form action="<?php print $_SERVER['PHP_SELF']; ?>" 
      method="post"
      id="frmRegister">
			
<fieldset class="wrapper">
  <legend>Register</legend>
  <p>Please fill out the following registration form <span class='required'></span>.</p>

<!--<fieldset class="intro">
<legend>Please complete the following form</legend> -->

<fieldset class="contact">
<legend>Personal Information</legend>	

        <label for="txtHouseholdName" class="required">Household Name</label>
  	<input type="text" id="txtHouseholdName" name="txtHouseholdName" value="<?php echo $householdname; ?>"
    		tabindex="110" maxlength="30" placeholder="Enter your household name" onfocus="this.select()" >
        
	<label for="txtUsername" class="required">Name</label>
  	<input type="text" id="txtUsername" name="txtUsername" value="<?php echo $username; ?>" 
    		tabindex="100" maxlength="30" placeholder="Enter your name" autofocus onfocus="this.select()" >
				
	<label for="txtEmail" class="required">Email</label>
  	<input type="email" id="txtEmail" name="txtEmail" value="<?php echo $email; ?>"
    		tabindex="110" maxlength="65" placeholder="Enter a valid email address" onfocus="this.select()" >
        
       

</fieldset>	

<fieldset class="bill">
<legend>Expense Information</legend>					
	<label for="txtAmount" class="required">Total Amount of Bill</label>
  	<input type="text" id="txtAmount" name="txtAmount" value="<?php echo $amount; ?>" 
    		tabindex="100" maxlength="25" placeholder="Enter your the amount of the expense" autofocus onfocus="this.select()" >
        
        	<label for="txtBillName" class="required">Give your bill an identifiable title</label>
  	<input type="text" id="txtBillName" name="txtBillName" value="<?php echo $billname; ?>" 
         tabindex="100" maxlength="25" placeholder="Enter the title of the expense" autofocus onfocus="this.select()" >
		

</fieldset>



<fieldset class="radio">
	<legend>Select type of expense</legend>
	<label><input type="radio" id="radElec" name="radType" value="Electricity" checked tabindex="231" 
			<?php if($type=="Electricity" || !$type) echo ' checked="checked" ';?>>Electricity</label>
      
	<label><input type="radio" id="radGas" name="radType" value="Gas" tabindex="233" 
			<?php if($type=="Gas") echo ' checked="checked" ';?>>Gas</label>
        
        <label><input type="radio" id="radInternet" name="radType" value="Internet" tabindex="233" 
			<?php if($type=="Internet") echo ' checked="checked" ';?>>Internet</label>
</fieldset>


<fieldset class="radio">
	<legend>Have you paid the expense yet?</legend>
	<label><input type="radio" id="radPaid" name="radPaid" value="Paid" checked tabindex="231" 
            <?php if ($paid=="Paid" || !$paid) echo ' checked="checked" ';?>>Paid</label>
            
	<label><input type="radio" id="radNotPaid" name="radPaid" value="NotPaid" tabindex="233" 
			<?php if($paid=="NotPaid") echo ' checked="checked" ';?>>Not paid</label>
</fieldset>

<fieldset class="radio">
	<legend>Have you lost the receipt of the expense?</legend>
	<label><input type="radio" id="radLost" name="radLost" value="Lost" checked tabindex="231" 
            <?php if ($paid=="Lost" || !$lost) echo ' checked="checked" ';?>>Lost</label>
            
	<label><input type="radio" id="radNotLost" name="radLost" value="NotLost" tabindex="233" 
			<?php if($lost=="NotLost") echo ' checked="checked" ';?>>Not lost</label>
</fieldset>



<fieldset class="checkbox">
	<legend>If the expense has already been paid paid, identify how it was paid (check all that apply):</legend>
  	<label><input type="checkbox" id="chkCash" name="chkCash" value="Cash" tabindex="221" 
			<?php if($cash) echo ' checked="checked" ';?>> Cash</label>
            
	<label><input type="checkbox" id="chkCredit" name="chkCredit" value="Check" tabindex="223" 
			<?php if($credit) echo ' checked="checked" ';?>> Credit</label>
        
        <label><input type="checkbox" id="chkCheck" name="chkCheck" value="Check" tabindex="223" 
			<?php if($check) echo ' checked="checked" ';?>> Check</label>
</fieldset>


<fieldset class="lists">	
	<legend>What month is this bill for?</legend>
	<select id="lstMonth" name="lstMonth" tabindex="281" size="1">
		<option value="January" <?php if($month=="January") echo ' selected="selected" ';?>>January</option>
		<option value="February" <?php if($month=="February") echo ' selected="selected" ';?>>February</option>
		<option value="March" <?php if($month=="March") echo ' selected="selected" ';?>>March</option>
                <option value="April" <?php if($month=="April") echo ' selected="selected" ';?>>April</option>
		<option value="May" <?php if($month=="May") echo ' selected="selected" ';?>>May</option>
		<option value="June" <?php if($month=="June") echo ' selected="selected" ';?>>June</option>
                <option value="July" <?php if($month=="July") echo ' selected="selected" ';?>>July</option>
		<option value="August" <?php if($month=="August") echo ' selected="selected" ';?>>August</option>
		<option value="September" <?php if($month=="September") echo ' selected="selected" ';?>>September</option>
                <option value="October" <?php if($month=="October") echo ' selected="selected" ';?>>October</option>
		<option value="November" <?php if($month=="November") echo ' selected="selected" ';?>>November</option>
		<option value="December" <?php if($month=="December") echo ' selected="selected" ';?>>December</option>
	</select>
</fieldset>

<fieldset class="buttons">
				
	<input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="991" class="button">

	<input type="reset" id="butReset" name="butReset" value="Reset Form" tabindex="993" class="button" onclick="reSetForm()" >
</fieldset>					

</fieldset>
</form>
        <?php
    } // end body submit
    ?>
</section>



<?php
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</body>
</html>