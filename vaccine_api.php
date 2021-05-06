


<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");

$entityBody = file_get_contents('php://input');
$data = json_decode($entityBody);

$Authkey = "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh";
if ($Authkey!=$data->auth){
    die("UNAUTHENTICATED");
}
else{
    $sql = $data->sql;

    //connecting to database           
    $link = mysql_connect('linkupbeatzcom.domaincommysql.com', 'distributor1', 'covid2021');
    if (!$link) { 
        die('Could not connect: ' . mysql_error()); 
    }
    mysql_set_charset("utf8mb4", $link);
    mysql_select_db("vaccine_db", $link);

    //execute sql command
    $result = mysql_query($sql, $link);
    $toReturn = new stdClass();
    if ($result === FALSE) {
        $toReturn->status = 'ERROR';
        $toReturn->message = mysql_error();
        $toReturn->sql = $sql;
        $myJSON = json_encode($toReturn);
        echo($myJSON);
    } else {
        $toReturn->status = 'SUCCESS';
        $toReturn->message = "New record inserted successfully";
        $toReturn->num_rows = mysql_num_rows($result);
        $rows = array();
        while($row = mysql_fetch_array($result)){
            array_push($rows, $row);
        }
        $toReturn->rows = $rows;
        $myJSON = json_encode($toReturn);
        echo($myJSON);
    }

    //close the connection
    mysql_close($link);
}


?>