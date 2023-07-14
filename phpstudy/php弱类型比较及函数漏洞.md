# php弱类型比较及函数漏洞

# sha1()md5()加密函数漏洞缺陷

**原理**：md5()和sha1()对一个数组进行加密将返回NULL

```php
if ($_GET['name'] == $_GET['password'])
    print 'Your password can not be your name.';
else if (sha1($_GET['name']) === sha1($_GET['password']))
    die('Flag: '.$flag);
```

构造：

?name[]=a&password[]=b

# 字符串处理函数漏洞缺陷

**原理**：strcmp()函数：比较两个字符串（区分大小写）

用法：

```php
int strcmp ( string $str1 , string $str2 )
```

解释用法：

```none
参数 `str1`第一个字符串。
参数 `str2`第二个字符串。
如果 `str1` 小于 `str2` 返回 `< 0`；
如果 `str1` 大于 `str2` 返回 `> 0`；
如果两者相等，返回 0。
```

这个函数接受到了不符合的类型，例如`数组`类型，函数将发生错误。但是在 `5.3` 之前的 php 中，显示了报错的警告信息后，将 `return 0` !!!! 也就是虽然报了错，但却判定其相等了。

* ereg()函数：字符串正则匹配
* strpos()函数：查找字符串在另一个字符串中第一次出现的位置，对大小写敏感。

这 2 个函数都是用来处理字符串的，但是在传入**数组参数**后都将返回 `NULL`。

例子：

```php
<?php
    error_reporting(0);
    $flag = 'flag{P@ssw0rd_1s_n0t_s4fe_By_d0uble_Equ4ls}';
    if (isset ($_GET['password'])) {  
        if (ereg ("^[a-zA-Z0-9]+$", $_GET['password']) === FALSE)  
            echo 'You password must be alphanumeric';  
        else if (strpos ($_GET['password'], '--') !== FALSE)  
            die($flag);  
        else  
            echo 'Invalid password';  
    }  
?>
```

构造：

```none
http://localhost/?password[]=gg
```

`ereg()函数`是处理字符串的，传入数组后返回 `NULL`，`NULL` 和 `FALSE`，是不恒等（===）的，满足第一个 `if` 条件；而 `strpos()函数`也是处理字符串的，传入数组后返回 `NUL`L，`NULL!==FALSE`，满足条件，拿到 flag

# parse_str函数变量覆盖缺陷

理论：`parse_str` 函数的作用就是**解析字符串并注册成变量**，*在注册变量之前不会验证当前变量是否存在，所以直接覆盖掉已有变量。*

```php
void parse_str ( string $str [, array &$arr ] )
```

str 输入的字符串。 arr 如果设置了第二个变量 arr，变量将会以数组元素的形式存入到这个数组，作为替代。

实例：

```php
<?php
error_reporting(0);
$flag = 'flag{V4ri4ble_M4y_Be_C0verEd}';
if (empty($_GET['b'])) {
    show_source(__FILE__);
    die();
}else{
    $a = "www.sqlsec.com";
    $b = $_GET['b'];
    @parse_str($b);
    if ($a[0] != 'QNKCDZO' && md5($a[0]) == md5('QNKCDZO')) {
        echo $flag;
    }else{
        exit('your answer is wrong~');
    }
}
?>
```

```php
@parse_str($b);
这里使用了parse_str函数来传递b的变量值
if ($a[0] != 'QNKCDZO' && md5($a[0]) == md5('QNKCDZO'))
这里用到的是文章上面的知识点md5()函数缺陷
```

构造：

```none
http://localhost/?b=a[0]=240610708
```

# json绕过

```
<?php
if (isset($_POST['message'])) {
    $message = json_decode($_POST['message']);
    $key ="*********";
    if ($message->key == $key) {
        echo "flag";
    } 
    else {
        echo "fail";
    }
 }
 else{
     echo "~~~~";
 }
?>
```

输入一个json类型的字符串，json_decode函数解密成一个数组，判断数组中key的值是否等于 $key的值，但是$key的值我们不知道，**但是可以利用0=="admin"这种形式绕过**

**最终payload message={"key":0}**

# array_search is_array绕过

```
1 <?php
 2 if(!is_array($_GET['test'])){exit();}
 3 $test=$_GET['test'];
 4 for($i=0;$i<count($test);$i++){
 5     if($test[$i]==="admin"){
 6         echo "error";
 7         exit();
 8     }
 9     $test[$i]=intval($test[$i]);
10 }
11 if(array_search("admin",$test)===0){
12     echo "flag";
13 }
14 else{
15     echo "false";
16 }
17 ?>
```

上面是自己写的一个，先判断传入的是不是数组，然后循环遍历数组中的每个值，并且数组中的每个值不能和admin相等，并且将每个值转化为int类型，再判断传入的数组是否有admin，有则返回flag

payload test[]=0可以绕过

array_search()函数：

``mixed array_search ( mixed $needle , array $haystack [, bool $strict = false ] )``

$needle，$haystack必需，$strict可选 函数判断$haystack中的值是存在$needle，存在则返回该值的键值 第三个参数默认为false，如果设置为true则会进行严格过滤

如果第三个参数为true，则相当于“===”的一样严格过滤，就不能绕过。

