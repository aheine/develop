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
    $myfile = fopen("q02.sql", "r") or die("Unable to open file!");
    $query = fread($myfile, filesize("q02.sql"));


    /* ##### Step three
     * Execute the query

     *      */
    $results = $thisDatabase->select($query);

    
     /* ##### Step four
     * prepare output and loop through array

     *      */
    $numberRecords = count($results);

    print "<h2>Total Records: " . $numberRecords . "</h2>";


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
//                    preg_replace(' /(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', substr($key, 3));
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
    
    
    ?>
</section>
</body>
</html>