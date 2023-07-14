<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>留言板</title>
</head>
<body>
    <form method="post" >
        <input type="text" name="content" placeholder="来留言吧....">
        <button>留言</button>
    </form>
<?php
    $server="localhost";
    $username="root";
    $password="root";
    $database="db_php";
    @$content=$_POST['content'];         //留言板POST传参
    $con=mysqli_connect($server,$username,$password,$database);
    $sql="INSERT INTO content(word)"."VALUE('$content')";
    $query=mysqli_query($con,$sql) or die("error");
    $sql = "SELECT * FROM content";
    $result = $con->query($sql);
    
   if ($result->num_rows > 0) {
       while($row=$result->fetch_assoc())
       {
        echo $row['word'];
        echo "<hr>";
       }
   } else {
       echo "0 结果";
    }
    mysqli_close($con);
?>
</body>
</html>