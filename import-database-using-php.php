<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
	$filename= 'abc.sql';
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'technogenous';
    $con=mysqli_connect($hostname,$username,$password);
    mysqli_query($con,"DROP DATABASE IF EXISTS ".$dbname) or die(mysqli_error($con));
    mysqli_query($con,"CREATE DATABASE ".$dbname) or die(mysqli_error($con));
    mysqli_select_db($con,$dbname) or die(mysqli_error($con));
    $sqlfile = file_get_contents($filename);
    $sqlstrings=explode(";",$sqlfile);
	foreach($sqlstrings as $line) {
	   	mysqli_query($con,$line);
	}
?>
