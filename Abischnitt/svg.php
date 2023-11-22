<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<bo0dy>
  <?php
    $sql="SELECT Note FROM fachhalbjahr WHERE S_ID=? AND Halbjahr=? AND Einbringen=1";
    $y=$db->prepare($sql);
    $durchschnitte=[];
    for ($i=0; $i < 4; $i++) {
      $sum=0;
      $y->execute([$s_id,$i+1]);
      $noten=$y->fetchAll(PDO::FETCH_BOTH);
      if(count($noten)>0)
      {
        for ($u=0; $u < count($noten); $u++) {
          $sum+=$noten[$u][0];
        }
        $durchschnitt=$sum/count($noten);
      }
      else{
        $durchschnitt=0;

      }
      $durchschnitte[]=$durchschnitt;
    }
    $ywerte=[];
    for ($i=0; $i < count($durchschnitte); $i++) {
      $y=444-(22.5*$durchschnitte[$i]);
      $ywerte[]=$y;
    }
   ?>
</body>
</html>
