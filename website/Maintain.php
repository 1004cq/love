<!DOCTYPE html>
<html lang="ZH">
<?php
if (!defined('Maintain')) {
  $ErrMsg = '你当前无权访问本页 请重试！';
  include_once '404.php';
}
?>
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title><?php echo $diy['SmTitie'] ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../Style/css/Maintain.css?=v<?php echo $versionCode ?>" />
</head>

<body style="background: url('<?php echo $diy['SmBgUrl'] ?>')center no-repeat;background-size: cover;">
  <canvas id="bg"></canvas>
  <main class="container">
    <div class="headText">
      <div class="InfoImg">
        <img draggable="false" src="Style/img/xuewang.png" alt="">
      </div>
      <h3 class="maintainTitle"><?php echo $diy['SmTitie'] ?></h3>
      <p class="quote" id="random-quote"><?php echo $diy['SmText'] ?></p>
    </div>

    <div class="BottomContent">
      <footer>
        <p class="copyright">
          Copyright© 2024 <?php echo $text['Copyright'] ?> All Rights Reserved
        </p>
      </footer>
    </div>
  </main>
  <script src="../Style/js/StarrySky.js"></script>
  <script src="../Style/js/Sentence.js"></script>
  <script src="../Style/js/Maintain.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      setInterval(() => getRandomQuoteAndDisplay(50, '<?php echo$diy['SmText'] ?>'), 5000);
    });
  </script>
</body>

</html>