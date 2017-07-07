<html>
<head>
   <?= $v->htmlAppBase(); ?>
   <?= $v->includeCSS('default'); ?>
</head>
<body>
   <div style="width: 100%; text-align: center; background-color: lightgreen;">
         <h5>Este es el Layout test2</h5>
         <?php $v->viewContent(); ?>
   </div>
</body>
</html>
