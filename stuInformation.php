<?php
    $service='mysql:host=localhost;dbname=curl;charset=utf8';
    $name='root';
    $code='';
    $pdo=new PDO($service,'root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


  	$url = 'http://jwzx.cqupt.congm.in/jwzxtmp/showBjStu.php?bj=ZM1604';
  	$ch = curl_init();
  	$useragent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36";
    $opt =  array(
		    CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 30,   
        CURLOPT_FOLLOWLOCATION => TRUE, 
        CURLOPT_USERAGENT => $useragent  
          );
    curl_setopt_array($ch, $opt);
  	$output = curl_exec($ch);
/*  	echo $output;*/
  	$string = $output;
  	$pattern = '/<tbody>.*<\/tbody>/';
    preg_match($pattern,$string,$result);
    $result=str_replace(array('<tbody>','</tbody>','</td>','</tr>'), '', $result);
/*    var_dump($result);
*/  $result=explode('<tr>', $result[0]);
/*    var_dump($result);*/


  	for ($i=0,$j=0,$StuInfo=array(); $i <count($result) ; $i++)
    { 
      if(empty($result[$i]))
        continue;
      else
      {
        $temp=explode('<td>', $result[$i]);
        for($j=0,$k=0,$TempInfo=array();$j<count($temp);$j++)
        {
          if(empty($temp[$j]))
            continue;
          else
          {
            $TempInfo[$k++]=$temp[$j];
            var_dump($temp[$j]);
          }
        }
        var_dump($TempInfo);
        $demo = $pdo->prepare("INSERT INTO student_information(no,num,name,gender,class,major,majorName,academy,grade,condition)VALUES(?,?,?,?,?,?,?,?,?,?)");
        $demo->bindValue(1,$TempInfo[0],PDO::PARAM_STR);
        $demo->bindValue(2,$TempInfo[1],PDO::PARAM_STR);
        $demo->bindValue(3,$TempInfo[2],PDO::PARAM_STR);
        $demo->bindValue(4,$TempInfo[3],PDO::PARAM_STR);
        $demo->bindValue(5,$TempInfo[4],PDO::PARAM_STR);
        $demo->bindValue(6,$TempInfo[5],PDO::PARAM_STR);
        $demo->bindValue(7,$TempInfo[6],PDO::PARAM_STR);
        $demo->bindValue(8,$TempInfo[7],PDO::PARAM_STR);
        $demo->bindValue(9,$TempInfo[8],PDO::PARAM_STR);
        $demo->bindValue(10,$TempInfo[9],PDO::PARAM_STR);
        $demo->execute();
        /*$demo->debugDumpParams();*/
    }
  }


       curl_close($ch);

?>
