<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <form method="post">
  <input type="checkbox" name="abwahl[]" value=11 checked>
  </input>
  <input type="checkbox" name="abwahl[]" value="abwahla" checked>
  </input>
<input type="submit">


  </form>
  <?php
  if(false)
  {
  function noten($x)
  {
    return $x;
  }
}
$arr=$_POST["abwahl"];
echo $arr[0]/2;
print_r($arr);
$x=2;
if(function_exists("noten"))
  {
    print "yes";
  }
  ?>
</body>
</html>
