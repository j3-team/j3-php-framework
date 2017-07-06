<html>
<head>
   <?= $v->htmlAppBase(); ?>
   <link rel="stylesheet" type="text/css" href="resources/css/test1.css" />
</head>
<body>
   <div style="border: 1px solid gray; width: 100%; text-align: center; background-color: lightgreen;">
         <h5>Este es el Layout test2</h5>
         <?php $v->viewContent(); ?>
   </div>
</body>
</html>
