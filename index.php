<?php
function http($url,$head,$method="GET",$data="",$h=0){
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
if($method!="GET"){
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
if($h===1){
curl_setopt($ch, CURLOPT_HEADER, 1);
}
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
return $result;
}

function post($url,$data,$head){
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
return $result;
}

function get($url,$head){
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
return $result;
}

function combi($set="ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",$x){
$array_elems_to_combine = str_split($set);
$size = $x;
$current_set = array('');
for ($i = 0; $i < $size; $i++) {
    $tmp_set = array();
    foreach ($current_set as $curr_elem) {
        foreach ($array_elems_to_combine as $new_elem) {
            $tmp_set[] = $curr_elem . $new_elem;
        }
    }
    $current_set = $tmp_set;
}
return $current_set;
}

function rando($length=16){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function rand_hex($length=16){
$bin = openssl_random_pseudo_bytes($length/2);
$hex = md5($bin);
$rand = substr($hex,1,$length);
return $rand;
}

function uuid() {
    $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
                       
                        
function pan($ln){
$pan = chr(mt_rand(65,75)).chr(mt_rand(65,91)).chr(mt_rand(65,91))."P".$ln[0].mt_rand(1000,9999).chr(mt_rand(65,75));
if(preg_match("/[A-Z]{3}P$ln[0][0-9]{4}[A-Z]{1}/",$pan)){
    return $pan;
    }
}


function getline($filen,$line) {
$file = new SplFileObject($filen);
if (!$file->eof()) {
     $file->seek($line-1);
     if($file->valid()){
     $contents = trim($file->current());
     return $contents; 
     }
     else echo "EOF\n";
}
}
        
        
        
function MCurl2($url,$head,$data="",$post=1,$h=0){
$response = array();
$ch = array();
$mh = curl_multi_init();
$keys = array_keys($url);
foreach($keys as $i){
	$u = $url[$i];
$ch[$i] = curl_init($u);
curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1);
if($post==1){
curl_setopt($ch[$i], CURLOPT_POST, 1);
curl_setopt($ch[$i], CURLOPT_POSTFIELDS, $data[$i]);
}
if($h==1){
curl_setopt($ch[$i], CURLOPT_HEADER, 1);
}
curl_setopt($ch[$i], CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch[$i], CURLOPT_HTTPHEADER, $head[$i]);
curl_multi_add_handle($mh,$ch[$i]);
}
  $running = null;
  do {
    $r = curl_multi_exec($mh, $running);
  } while($running);
  if($r != CURLM_OK){
	  echo "\nError: ".curl_multi_strerror($r)."\n";
  }
  foreach($keys as $j){
      curl_multi_remove_handle($mh, $ch[$j]);
	  $error = curl_getinfo($ch[$j], CURLINFO_HTTP_CODE);
	  if($error == 200){
      $response[$j] = curl_multi_getcontent($ch[$j]);
	  }
	  else $response[$j] = $error;//."||".curl_multi_getcontent($ch[$j]);
  }
 return $response;
}
