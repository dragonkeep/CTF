<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>提交成功</title>
</head>
<body>
    <center style="width: 600px;margin: 0 auto;line-height: 30px;">
        <h2>提交成功！</h2>
<?php
$link=mysqli_connect("localhost","root","root","db_php") or die("数据块连接失败");
if($link){echo "您的信息已经收到，我们会尽快处理！";}else{echo "连接失败";}
@$department=$_POST['$department'];
@$name=$_POST['name'];
@$suggestion=$_POST['$suggestion'];
$query="INSERT INTO suggestion(department,name,suggestion)"."VALUE('$department','$name','$suggestion')";
$result=mysqli_query($link,$query)or die("插入失败");
$query="SELECT * FROM suggestion";
$result=mysqli_query($link,$query) or die("读取数据失败！");
echo '<table><tr><th>'."编号".'</th><th>'."科室"."</th><th>"."姓名"."</th<th>"."建议".
"</th></tr>";
$i=1;
while($row=mysqli_fetch_array($result)){
    echo '<tr><td>'.$i.'</td>';
    echo '<td>'.$row['department'].'</td>';
    echo '<td>'.$row['name'].'</td>';
    echo '<td>'.$row['suggestion'].'</td></tr>';
    $i++;
}
echo "</table>";
mysqli_close($link);


/*插入数据
$link=mysqli_connect("localhost","root","root","db_php") or die("数据块连接失败");
if($link){echo "成功连接到数据库！";}else{echo "连接失败";}
@$department=$_POST['$department'];
@$name=$_POST['name'];
@$suggestion=$_POST['$suggestion'];
$query="INSERT INTO suggestion(department,name,suggestion)"."VALUE('$department','$name','$suggestion')";
$result=mysqli_query($link,$query)or die("插入失败");
*/


/*
 //连接数据库
$link=mysqli_connect("localhost","root","root","db_php") or die("连接失败！");  //参数1是地址，可以是域名，参数2是用户名，参数3是密码，参数4是数据库名
if($link){
    echo 'success!';
}
$sql="CREATE TABLE suggestion(
    department VARCHAR(10) not null,
    name VARCHAR(10) not null,
    suggestion VARCHAR(600) not null
)";
//对数据库进行操作
// mysql_query()的参数1是sql语句，参数2是连接变量
if(mysqli_query($link,$sql)){   //参数1是连接变量，参 数2是sql语句
    echo "suggestion have created success!";
}else{echo "creat error!";}   
mysqli_close($link);      //关闭连接
*/




/*
       $department=$_POST['$department'];
       $name=$_POST['name'];
       $suggestion=$_POST['$suggestion'];
       echo $department.'的'.$name.'<br/>';
       echo '您提交的信息是：'.$suggestion.'。<br/>感谢您的提交，我们会尽快处理！';
        */
       ?>
    </center>
</body>
</html>