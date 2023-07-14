# PHP变量覆盖

变量覆盖漏洞通常是使用外来参数替换或者初始化程序中原有变量的值

### 函数使用不当

1. extract()函数

extract()函数：将一个关联数组的键值对转换为变量和对应的值。

```php
$auth=false;
extract($_GET);
```

如果extract函数包含GET且没有指明参数，可以构造：

`?auth=1`来改变程序中原有的$auth的值。

2. parse_str()函数

`parse_str($_SERVER['QUERY_STRING'])` 是一个 PHP 函数调用，用于解析 URL 查询字符串，并将其解析结果存储在相应的变量中。

```php
$auth=false;
parse_str($_SERVER['QUERY_STRING']);
```

同样，传入`?auth=1`来改变程序中原有的$auth的值。

3. import_request_variables函数

函数值有`G`,`P`,`C`，分别代表GET，POST，Cookie

```php
$auth=false;
import_request_variables('G');
```

在php5.4之后被移除，无法使用。

### 配置不当

在PHP版本小于5.4的时候，如果PHP的配置`register_globals=ON`的话，且满足变量被未初始化，即可使用GET传入参数进行修改变量的值。

```php
<?php 
include("flag.php");
highlight_file(__FILE__);
error_reporting(0);        
if($auth){
    echo $flag;
}else{
    echo "Access Denied.";
}
```

### 代码逻辑漏洞

基础：

PHP的可变变量$$：可以让一个普通变量的值作为这个可变变量的变量名。

```php
$foo="hello";
$$foo="world";
echo ${$foo};   	//输出world
echo $hello; 		//同样输出world
```

开发者如果使用foreach进行遍历数组：

```php
$auth=false;
foreach($_GET as $key=>$value)
{
    $$key=$value;
}
if($auth){
    echo $flag;
}else{
    echo "Access Denied.";
}
```

同样，传入`?auth=1`来改变程序中原有的$auth的值。

$key的值为‘auth',$$key=$auth=1,这样就能实现变量的覆盖。