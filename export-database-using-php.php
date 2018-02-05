<?php
	defined('DB_HOST') ? null : define('DB_HOST', 'localhost');

	defined('DB_USER') ? null : define('DB_USER', 'root');

	defined('DB_PASSWORD') ? null : define('DB_PASSWORD', '');

	defined('DB_NAME') ? null : define('DB_NAME', 'nadkarnita');

	$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
	
	function query($query) {
		global $conn;
		return $conn->prepare($query);
	}

    $tables = array();
    $content = array();

    $sel_query = query("SHOW TABLES");
    $sel_query->execute();
    while($row = $sel_query->fetch(PDO::FETCH_BOTH)) {
        $tables[] = $row[0];
    }

    foreach($tables as $table) {
        $sel_query1 = query("SHOW CREATE TABLE ".$table);
        $sel_query1->execute();
        $row1 = $sel_query1->fetch(PDO::FETCH_BOTH);
        $bundle = $row1[1].";\n\n";

        $i = 0;
        $sel_query = query("SELECT * from ".$table);
        $sel_query->execute();
        if($sel_query->rowCount()>0) :
            $bundle .= "INSERT INTO ".$table." VALUES";
            while($row = $sel_query->fetch(PDO::FETCH_BOTH)) {
                $column_c = $sel_query->columnCount();
                $rows_c = $sel_query->rowCount();
                $bundle .= "\n(";
                for($j = 0;$j<$column_c; $j++) {
                    $comma = ", ";
                    if($j == $column_c-1) :
                        $comma = "";
                    endif;
                    $bundle .= '"'.addslashes($row[$j]).'"'.$comma;
                }
                if($i == $rows_c-1) :
                    $bundle .= ");\n\n\n\n";
                else :
                    $bundle .= "),";
                endif;       
            $i++;}
        endif;
        $content[] = $bundle;
    }
   // $content = implode("\n\n", $content);
    //echo $content;
    $name = DB_NAME;
    $content = implode("", $content);
    $backup_name = $name . "___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";

    header('Content-Type: application/octet-stream');   header("Content-Transfer-Encoding: Binary"); header("Content-disposition: attachment; filename=\"".$backup_name."\"");  echo $content; exit;
?>