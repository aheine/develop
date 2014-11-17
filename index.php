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
$debug = true;
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
$username = "";
$month = "";
$type = "electricity";
$amount = "";
$paid = "yes";
$lost = "no";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$emailERROR = false;
$usernameERROR = false;
$amountERROR = false;


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
$month = htmlentities($_POST["lstMonth"], ENT_QUOTES, "UTF-8");
$type = htmlentities($_POST["radType"], ENT_QUOTES, "UTF-8");
$amount = htmlentities($_POST["txtAmount"], ENT_QUOTES, "UTF-8");
$paid = htmlentities($_POST["radPaid"], ENT_QUOTES, "UTF-8");
$lost = htmlentities($_POST["radLost"], ENT_QUOTES, "UTF-8");

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
        if ($amount == "") {
        if($debug) print 'Amount is blank';
        $errorMsg[] = "Please enter the amount";
        $amountERROR = true;
    } elseif (!verifyAlphaNum($amount)){
        $errorMsg[] = "Your username must be only letters and numbers";
        $amountERROR = true;
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
            $query = 'INSERT INTO tblRecord (fldEmail, fldUsername, fldMonth, fldType, fldAmount, fldPaid, fldLost) VALUES (?, ?, ?, ?, ?, ?, ?)';
            $data = array($email, $username, $month, $type, $amount, $paid, $lost); 
            if ($debug) {
                print "<p>sql " . $query;
                print"<p><pre>";
                print_r($data);
                print"</pre></p>";
            }
            $results = $thisDatabase->insert($query, $data);

            $primaryKey = $thisDatabase->lastInsert();
            //if ($debug)


// all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();
            $dataEntered = true;
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

//            $query = "SELECT fldDateJoined FROM tblRegister WHERE pkRegisterId=" . $primaryKey;
//            $results = $thisDatabase->select($query);
//
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
//
//            $messageA = '<h2>Thank you for registering.</h2>';
//
//            $messageB = "<p>Click this link to confirm your registration: ";
//            $messageB .= '<a href="' . $domain . $path_parts["dirname"] . '/confirmation.php?q=' . $key1 . '&amp;w=' . $key2 . '">Confirm Registration</a></p>';
//            $messageB .= "<p>or copy and paste this url into a web browser: ";
//            $messageB .= $domain . $path_parts["dirname"] . '/confirmation.php?q=' . $key1 . '&amp;w=' . $key2 . "</p>";

//            $messageC .= "<p><b>Email Address:</b><i>   " . $email . "</i></p>";

            //##############################################################
            //
            // email the form's information
            //
            $to = $email; // the person who filled out the form
            $cc = "";
            $bcc = "";
            $from = "Alice's Site <noreply@yoursite.com>";
            $subject = "CS 148 Form";

            

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
<article id="main">
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
<legend>Your Information</legend>					
	<label for="txtUsername" class="required">Name</label>
  	<input type="text" id="txtUsername" name="txtUsername" value="<?php echo $username; ?>" 
    		tabindex="100" maxlength="25" placeholder="Enter your name" autofocus onfocus="this.select()" >
				
	<label for="txtEmail" class="required">Email</label>
  	<input type="email" id="txtEmail" name="txtEmail" value="<?php echo $email; ?>"
    		tabindex="110" maxlength="45" placeholder="Enter a valid email address" onfocus="this.select()" >
        
    <!--   <label for="txtMisc" class="required">Misc value</label>
  	<input type="text" id="txtMisc" name="txtMisc" value="<?php echo $misc; ?>"
    		tabindex="110" maxlength="3" placeholder="enter a valid whole number 0 to 100" onfocus="this.select()" > -->

</fieldset>	

<fieldset class="bill">
<legend>Bill amount</legend>					
	<label for="txtAmount" class="required">Amount</label>
  	<input type="text" id="txtAmount" name="txtAmount" value="<?php echo $amount; ?>" 
    		tabindex="100" maxlength="25" placeholder="Enter your the amount of the bill" autofocus onfocus="this.select()" >
		

</fieldset>



<fieldset class="radio">
	<legend>Select type of bill</legend>
	<label><input type="radio" id="radElec" name="radType" value="Electricity" tabindex="231" 
			<?php if($type=="Electricity" || !$type) echo ' checked="checked" ';?>>Electricity</label>
      
	<label><input type="radio" id="radGas" name="radType" value="Gas" tabindex="233" 
			<?php if($type=="Gas") echo ' checked="checked" ';?>>Gas</label>
        
        <label><input type="radio" id="radInternet" name="radType" value="Internet" tabindex="233" 
			<?php if($type=="Internet") echo ' checked="checked" ';?>>Internet</label>
</fieldset>


<fieldset class="radio">
	<legend>Have you paid the bill yet?</legend>
	<label><input type="radio" id="radPaid" name="radPaid" value="Paid" tabindex="231" 
            <?php if ($paid=="Paid" || !$paid) echo ' checked="checked" ';?>>Paid</label>
            
	<label><input type="radio" id="radNotPaid" name="radPaid" value="NotPaid" tabindex="233" 
			<?php if($paid=="NotPaid") echo ' checked="checked" ';?>>Not paid</label>
</fieldset>

<fieldset class="radio">
	<legend>Have you lost to receipt of the bill?</legend>
	<label><input type="radio" id="radLost" name="radLost" value="Lost" tabindex="231" 
            <?php if ($paid=="Lost" || !$lost) echo ' checked="checked" ';?>>Lost</label>
            
	<label><input type="radio" id="radNotLost" name="radLost" value="NotLost" tabindex="233" 
			<?php if($lost=="NotLost") echo ' checked="checked" ';?>>Not lost</label>
</fieldset>


<!--<fieldset class="checkbox">
	<legend>Do you (check all that apply):</legend>
  	<label><input type="checkbox" id="chkTypea" name="chkTypea" value="Alpine" tabindex="221" 
			<?php if($typea) echo ' checked="checked" ';?>> Alpine ski</label>
            
	<label><input type="checkbox" id="chkTypen" name="chkTypen" value="Nordic" tabindex="223" 
			<?php if($typen) echo ' checked="checked" ';?>> Nordic ski</label>

</fieldset>

<fieldset class="checkbox">
	<legend>Which Vermont ski mountains do you like? (check all that apply):</legend>
  	<label><input type="checkbox" id="chkStowe" name="chkStowe" value="Stowe" tabindex="221" 
			<?php if($stowe) echo ' checked="checked" ';?>> Stowe</label>
            
	<label><input type="checkbox" id="chkSugarbush" name="chkSugarbush" value="Sugarbush" tabindex="223" 
			<?php if($sugarbush) echo ' checked="checked" ';?>> Sugarbush</label>
        
        <label><input type="checkbox" id="chkMadriver" name="chkMadriver" value="Madriver" tabindex="223" 
			<?php if($madriver) echo ' checked="checked" ';?>> Mad River Glen</label>
</fieldset>-->


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
	<legend></legend>				
	<input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="991" class="button">

	<input type="reset" id="butReset" name="butReset" value="Reset Form" tabindex="993" class="button" onclick="reSetForm()" >
</fieldset>					

</fieldset>
</form>
        <?php
    } // end body submit
    ?>
</article>



<?php
include "footer.php";
if ($debug)
    print "<p>END OF PROCESSING</p>";
?>
</body>
</html>