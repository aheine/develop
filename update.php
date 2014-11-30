<?php
/* the purpose of this page is to display a form to allow a poet and allow us
 * to add a new poet or update an existing poet 
 * 
 * Written By: Robert Erickson robert.erickson@uvm.edu
 * Last updated on: November 20, 2014
 * 
 */

include "top.php";
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1 Initialize variables
$update = false;

// SECTION: 1a.
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

if (isset($_GET["id"])) {
    $pmkBillName = htmlentities($_GET["id"], ENT_QUOTES, "UTF-8");

    $query = 'SELECT fldType, fldPaid, fldLost, fldAmount, fldMonth ';
    $query .= 'FROM tblBills WHERE pmkBillName = ?';

    $results = $thisDatabase->select($query, array($pmkBillName));

    $type = $results[0]["fldType"];
    $paid = $results[0]["fldPaid"];
    $lost = $results[0]["fldLost"];
    $amount = $results[0]["fldAmount"];
    $month = $results[0]["fldMonth"];
} else {
    $pmkBillName = -1;
    $type = "";
    $paid = "";
    $lost = "";
    $amount = "";
    $month = "";
}

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c.
$typeERROR = false;
$paidERROR = false;
$lostERROR = false;
$amountERROR = false;
$monthERROR = false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1e misc variables
//
// create array to hold error messages filled (if any) in 2d displayed in 3c.
$errorMsg = array();
$data = array();
$dataEntered = false;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2 Process for when the form is submitted
//
if (isset($_POST["btnSubmit"])) {
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2a Security
//
    /*    if (!securityCheck(true)) {
      $msg = "<p>Sorry you cannot access this page. ";
      $msg.= "Security breach detected and reported</p>";
      die($msg);
      }
     */
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2b Sanitize (clean) data
// remove any potential JavaScript or html code from users input on the
// form. Note it is best to follow the same order as declared in section 1c.
    $pmkBillName = htmlentities($_POST["hidBillName"], ENT_QUOTES, "UTF-8");
    if ($pmkBillName != "") {
        $update = true;
    }
    // I am not putting the ID in the $data array at this time

    $month = htmlentities($_POST["lstMonth"], ENT_QUOTES, "UTF-8");
    $data[] = $month;

    $type = htmlentities($_POST["radType"], ENT_QUOTES, "UTF-8");
    $data[] = $type;

    $amount = htmlentities($_POST["txtAmount"], ENT_QUOTES, "UTF-8");
    $data[] = $amount;
    
    $paid = htmlentities($_POST["radPaid"], ENT_QUOTES, "UTF-8");
    $data[] = $paid;
    
    $lost = htmlentities($_POST["radLost"], ENT_QUOTES, "UTF-8");
    $data[] = $lost;
    


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2c Validation
//
    
        if ($amount == "") {
        if($debug) print 'Amount is blank';
        $errorMsg[] = "Please enter the amount";
        $amountERROR = true;
    } elseif (!verifyAlphaNum($amount)){
        $errorMsg[] = "The amount must be only numbers";
        $amountERROR = true;
    }


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2d Process Form - Passed Validation
//
// Process for when the form passes validation (the errorMsg array is empty)
//
    if (!$errorMsg) {
        if ($debug) {
            print "<p>Form is valid</p>";
        }

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
//
// SECTION: 2e Save Data
//
        
        $typeERROR = false;
$paidERROR = false;
$lostERROR = false;
$amountERROR = false;
$monthERROR = false;

        $dataEntered = false;
        try {
            $thisDatabase->db->beginTransaction();

            if ($update) {
                $query = 'UPDATE tblBills SET ';
            } else {
                $query = 'INSERT INTO tblBills SET ';
            }

            $query .= 'fldType = ?, ';
            $query .= 'fldLost = ?, ';
            $query .= 'fldAmount = ?, ';
            $query .= 'fldMonth = ? ';

            if ($update) {
                $query .= 'WHERE pmkBillName = ?';
                $data[] = $pmkBillName;
                
                $results = $thisDatabase->update($query, $data);
            } else {
                $results = $thisDatabase->insert($query, $data);

                $primaryKey = $thisDatabase->lastInsert();
                if ($debug) {
                    print "<p>pmk= " . $primaryKey;
                }
            }

            // all sql statements are done so lets commit to our changes
            $dataEntered = $thisDatabase->db->commit();

            if ($debug)
                print "<p>transaction complete ";
        } catch (PDOExecption $e) {
            $thisDatabase->db->rollback();
            if ($debug)
                print "Error!: " . $e->getMessage() . "</br>";
            $errorMsg[] = "There was a problem with accpeting your data please contact us directly.";
        }
    } // end form is valid
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
    if ($dataEntered) { // closing of if marked with: end body submit
        print "<h1>Record Saved</h1> ";
    } else {
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
	

<fieldset class="bill">
<legend>Bill information</legend>					
	<label for="txtAmount" class="required">Amount</label>
  	<input type="text" id="txtAmount" name="txtAmount" value="<?php echo $amount; ?>" 
    		tabindex="100" maxlength="25" placeholder="Enter your the amount of the bill" autofocus onfocus="this.select()" >
        <input type="hidden" id="hidBillName" name="hidBillName"
     value="<?php print $pmkBillName; ?>"
>

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
</article>
</body>
</html>