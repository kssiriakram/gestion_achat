<!DOCTYPE html>
<html>
   <head>
      <title>Gmail Sender</title>
      <link rel="stylesheet" href="style.css">
   </head>
   <body>
      <div class="wrapper">
         <form method="post" action="index.php">
            <h2>Gmail Sender App</h2><br>
            Email To :<br>
            <input type="text" name="email"><br>
            Subject :<br>
            <input type="text" name="subject"><br>
            Body :<br>
            <textarea name="body"></textarea><br>
            <input type="submit" value="SEND" name="submit">            
         </form>
         <?php
         if(isset($_POST['submit'])){
           
         }
         ?>
      </div>
      
   </body>
</html>