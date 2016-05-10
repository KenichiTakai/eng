<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>search_result</title>
</head>
<body>

<?php
$con = mysql_connect('mysql511.heteml.jp', '_english32', 'eng31-32');
if (!$con) {
    die('not connected:' . mysql_error());
}
//echo 'connected';

$result = mysql_select_db('_english32', $con);
if (!$result) {
  exit(' !!! cannot select database');
}

$result = mysql_query('SET NAMES utf8', $con);
if (!$result) {
  exit('cannot select character code');
}

// Verify the language user searched
$LangFlag = '';
$Sword = $_POST['JP'];

//日本語か英数字かそれ以外か
  mb_regex_encoding("UTF-8");
  if (preg_match("/^[ぁ-んァ-ヶー一-龠]+$/u", $Sword)) {
    $LangFlag = 'Japanese';
  } elseif (preg_match('/^[a-zA-Z0-9]+$/', $Sword)) {
    $LangFlag = 'English';
  } else {
    exit('cannot verify the language entered');
  }

//日本語で検索したならDBの”日本語列”を検索しに行き、英語なら英語列を検索しに行く
//select文の * は改修が終わったら、カラム名に変える

if ($LangFlag == 'Japanese') {
$result = mysql_query("SELECT * FROM eng_master where jp like '%$_POST[JP]%' ", $con);
} elseif ( $LangFlag == 'English') {
$result = mysql_query("SELECT * FROM eng_master where en like '%$_POST[JP]%' ", $con);
}

//結果表示
$rows = mysql_num_rows($result);

//レコードがある場合の処理
if($rows >0){
while ($data = mysql_fetch_array($result)) {
  echo '<p>' . $data['ID'] . ' : ' . $data['jp'] . '<br>' . $data['en'] . "</p>\n";
}

//レコードがない場合の処理
}else{
print_r('検索結果0でした。');
}


//echo '<p>' . $data['ID'] . ' : ' . $data['en'] . "</p>\n";
//echo '<p>' . $data['ID'] . ' : ' . $data['jp'] . "</p>\n";

$con = mysql_close($con);
if (!$con) {
  exit('cannot close the connection to MySQL');
}

?>

</body>
</html>