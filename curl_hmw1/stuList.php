<?php
	//连接数据库
	$service = 'mysql:host=localhost;dbname=curl;charset=utf8';
	$name = 'root';
	$code = '';
	$pdo = new PDO($service, 'root', '');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//抓取信息
	//抓取整个网页
	$classId = ($_POST['classId']);
	$url = 'http://jwzx.cqupt.congm.in/jwzxtmp/showBjStu.php?bj='.$classId;
	/*var_dump($classId);*/
	$useragent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36";
	$ch = curl_init();
	$opt = array (
		CURLOPT_URL => $url,
		CURLOPT_USERAGENT => $useragent,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_TIMEOUT => 100,
		CURLOPT_FOLLOWLOCATION => TRUE
		);
	curl_setopt_array($ch, $opt);
	$output = curl_exec($ch);
	/*echo "$output";*/

	//抓取tbody标签内部分
	$subject = $output;
	preg_match('/<tbody>.*<\/tbody>/', $subject, $matches);
	/*var_dump($matches);*/

	//分割
	$matches = str_replace(array('<tbody>', '</tbody>', '</td>', '</tr>'), '', $matches);
/*	var_dump($matches);
*/	$matches = explode('<tr>', $matches[0]);
/*	var_dump($matches);
*/	for ($i=0, $j=0, $StuInfo=array(); $i <count($matches); $i++)
    { 
      if(empty($matches[$i]))
      	continue;
      else
      {
        $temp=explode('<td>', $matches[$i]);
        for($j=0,$k=0, $TempInfo=array(); $j<count($matches); $j++)
        {
          if(empty($temp[$j]))
            continue;
          else
          {
            $TempInfo[$k++] = $temp[$j];
            /*var_dump($temp[$j]);*/
          }
        }
/*        var_dump($TempInfo);
*/        try {
			$pdo = new PDO($service,$name,$code);
			$pdo -> setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        	/*$config = require_once'config.php';
	   		$pdo = new PDO($config['dsn'], $config['user'], $config['password']);*/
	    		$insert = $pdo -> prepare("INSERT INTO student_information (no, num, name, gender, class, major, majorName, academy, grade, `condition`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	    	$insert->bindParam(1,$TempInfo[0],PDO::PARAM_STR);
	    	$insert->bindParam(2,$TempInfo[1],PDO::PARAM_STR);
	    	$insert->bindParam(3,$TempInfo[2],PDO::PARAM_STR);
	    	$insert->bindParam(4,$TempInfo[3],PDO::PARAM_STR);
	    	$insert->bindParam(5,$TempInfo[4],PDO::PARAM_STR);
	   	 	$insert->bindParam(6,$TempInfo[5],PDO::PARAM_STR);
	    	$insert->bindParam(7,$TempInfo[6],PDO::PARAM_STR);
	   	 	$insert->bindParam(8,$TempInfo[7],PDO::PARAM_STR);
	    	$insert->bindParam(9,$TempInfo[8],PDO::PARAM_STR);
	    	$insert->bindParam(10,$TempInfo[9],PDO::PARAM_STR);
	    	$insert->execute();
	    	echo "已存入数据";
/*	    	var_dump($insert -> execute());
	    	var_dump($insert -> errorInfo());
 */   		}
    	catch (PDOException $e) {
        	echo "数据库错误：{$e->getMessage()}";
        }
    }
  }
       curl_close($ch);





?>
