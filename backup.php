<?php

$dominio = $_SERVER['HTTP_HOST'];

error_reporting(0);
include('atlas/conexao.php');
ini_set('display_errors',1); ini_set('display_startup_erros',1); error_reporting(E_ALL);//force php to show any error message

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//consulta token do usuario
$token = '5805527805:AAGGgmBS-uF6zj1E3NWeTvmjKp_QztD9Zws';
$idtelegram = '2017803306';


backup_tables($dbhost,$dbuser,$dbpass,$dbname);

function backup_tables($host,$user,$pass,$name){

    $link = mysqli_connect($host,$user,$pass);
    mysqli_select_db($link, $name);
        $tables = array();
        $result = mysqli_query($link, 'SHOW TABLES');
        $i=0;
        while($row = mysqli_fetch_row($result))
        {
            $tables[$i] = $row[0];
            $i++;
        }
    $return = "";
    foreach($tables as $table)
    {
        $result = mysqli_query($link, 'SELECT * FROM '.$table);
        $num_fields = mysqli_num_fields($result);
        $return .= 'DROP TABLE IF EXISTS '.$table.';';
        $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";
        for ($i = 0; $i < $num_fields; $i++)
        {
            while($row = mysqli_fetch_row($result))
            {
                $return.= 'INSERT INTO '.$table.' VALUES(';
                for($j=0; $j < $num_fields; $j++)
                {
                    $row[$j] = addslashes($row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j < ($num_fields-1)) { $return.= ','; }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }
    //save file
    $handle = fopen('atlasbackup.sql','w+');
    fwrite($handle, $return);
    fclose($handle);
}
require_once('vendor2/autoload.php');
use Telegram\Bot\Api;
$telegram = new Api($token);

$telegram->sendDocument([
    'chat_id' => $idtelegram,
    'document' => fopen('atlasbackup.sql', 'r'),
    'caption' => "Backup do banco de dados Gerenciador"
]);
unlink('atlasbackup.sql');
?>