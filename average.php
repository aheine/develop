<?php
$debug = false;
//############################################################################
//
// This page displays the results of a query that is located in a text file.
//
//############################################################################
?>

    <?php include "top.php" ?>
    
    <section>
        <h1>Total Average Household Expenses</h1>
    <?php
    
    /* ##### Step one 
     * 
     * create your database object using the appropriate database username

    */
    require_once('../bin/myDatabase.php');

    $dbUserName = get_current_user() . '_reader';
    $whichPass = "r"; //flag for which one to use.
    $dbName = strtoupper(get_current_user()) . '_Record';

    $thisDatabase = new myDatabase($dbUserName, $whichPass, $dbName);


    /* ##### html setup */
    
    $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
    $path_parts = pathinfo($phpSelf);
//    print '<body id="' . $path_parts['filename'] . '">';

    
    /* ##### Step two 
     * 
     * open the file that contains the query

    */
    $myfile = fopen("average.sql", "r") or die("Unable to open file!");
    $query = fread($myfile, filesize("average.sql"));


    /* ##### Step three
     * Execute the query

     *      */
    $results = $thisDatabase->select($query);

    
     /* ##### Step four
     * prepare output and loop through array

     *      */
    $numberRecords = count($results);


    print "<table>";

    $firstTime = true;

    /* since it is associative array display the field names */
    foreach ($results as $row) {
        if ($firstTime) {
            print "<thead><tr>";
            $keys = array_keys($row);
            foreach ($keys as $key) {
                if (!is_int($key)) {
                    print "<th>" . $key . "</th>";
                }
            }
            print "</tr>";
            $firstTime = false;
        }
        
        /* display the data, the array is both associative and index so we are
         *  skipping the index otherwise records are doubled up */
        print "<tr>";
        foreach ($row as $field => $value) {
            if (!is_int($field)) {
                print "<td>" . $value . "</td>";
            }
        }
        print "</tr>";
    }
    print "</table>";
    
    
    print "<h1>Average Household Expenses By Month</h1>";
    
    $myfile2 = fopen("averagebymonth.sql", "r") or die("Unable to open file!");
    $query2 = fread($myfile2, filesize("averagebymonth.sql"));


    /* ##### Step three
     * Execute the query

     *      */
    $results2 = $thisDatabase->select($query2);

    
     /* ##### Step four
     * prepare output and loop through array

     *      */
    $numberRecords2 = count($results2);


    print "<table>";

    $firstTime2 = true;

    /* since it is associative array display the field names */
    foreach ($results2 as $row) {
        if ($firstTime2) {
            print "<thead><tr>";
            $keys = array_keys($row);
            foreach ($keys as $key) {
                if (!is_int($key)) {
                    print "<th>" . $key . "</th>";
                }
            }
            print "</tr>";
            $firstTime2 = false;
        }
        
        /* display the data, the array is both associative and index so we are
         *  skipping the index otherwise records are doubled up */
        print "<tr>";
        foreach ($row as $field => $value) {
            if (!is_int($field)) {
                print "<td>" . $value . "</td>";
            }
        }
        print "</tr>";
    }
    print "</table>";
    
    
    
    
            print "<h1>Average Household Expenses By Type</h1>";
    
    $myfile3 = fopen("averagebytype.sql", "r") or die("Unable to open file!");
    $query3 = fread($myfile3, filesize("averagebytype.sql"));


    /* ##### Step three
     * Execute the query

     *      */
    $results3 = $thisDatabase->select($query3);

    
     /* ##### Step four
     * prepare output and loop through array

     *      */
    $numberRecords3 = count($results3);


    print "<table>";

    $firstTime3 = true;

    /* since it is associative array display the field names */
    foreach ($results3 as $row) {
        if ($firstTime3) {
            print "<thead><tr>";
            $keys = array_keys($row);
            foreach ($keys as $key) {
                if (!is_int($key)) {
                    print "<th>" . $key . "</th>";
                }
            }
            print "</tr>";
            $firstTime3 = false;
        }
        
        /* display the data, the array is both associative and index so we are
         *  skipping the index otherwise records are doubled up */
        print "<tr>";
        foreach ($row as $field => $value) {
            if (!is_int($field)) {
                print "<td>" . $value . "</td>";
            }
        }
        print "</tr>";
    }
    print "</table>";
    
    
    
    
    
    ?>
        
        
        
        
        
        </section>
</body>
</html>