<?php
# $ php -f db-connect-test.php

$dbname = 'api_risk';
$dbuser = 'root';
$dbpass = '';
$dbhost = 'localhost';
$dbport = '666';
$link = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname,$dbport) or die("Unable to Connect to '$dbhost'");
mysqli_select_db($link, $dbname) or die("Could not open the db '$dbname'");

$test_query = "SHOW TABLES FROM $dbname";
$result = mysqli_query($link, $test_query);

$tblCnt = 0;
while($tbl = mysqli_fetch_array($result)) {
    $tblCnt++;
    }
#the table is not empty
$this->assertNotEquals($tblCnt,0);
?>