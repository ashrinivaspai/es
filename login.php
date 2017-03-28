<?php
   include("dbcon.php");
   //include("style.php");
   session_start();
   $error="";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($connection,$_POST['username']);
      $mypassword = mysqli_real_escape_string($connection,$_POST['password']); 
      
      $sql = "SELECT USN, type FROM data WHERE name = '$myusername' and USN = '$mypassword'";
      $result = mysqli_query($connection,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

     // $active = $row['active'];
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1)
      {
         //session_register("myusername");
         $_SESSION['login_user'] = $myusername;
         $_SESSION['type']=$row['type'];
         $_SESSION['USN']=$row['USN'];
         if($row['type']=='1')
            header("location: entry_f.php");
         else 
            header("Location: student_1.php");
      }
      else 
      {
         $error = "Your Login Name/Password is invalid or you dont have authority to access this page";
      }
   }
?>
<html>
   
   <head>
   <link rel="stylesheet" href="w3.css">
   <div class="W3-container">
   <div class="w3-panel w3-border w3-padding">
      <table>
         <tr>
            <td align="left" width=20%><img src="nmamit.jpg" style="width: 50%"></td>
            <td  width: 75% align="center"><h1 style="text-align: center;">WELCOME TO NMAMIT STUDENT DATABASE SYSTEM</h1></td>
            <td align="right" width="20%"><img src="nitte-logo.png" style="width:55%" ></td>
         </tr>
      </table>
   </div>
   </div>
      <title>Login Page</title>
      
      
   </head>
   <Br>
   <div align="center">
   <div class="w3-card-4" style="width: 40%;">
      <div class="w3-container w3-blue">
      <h4>Login<h4>
      </div>
            <form method=POST action="login.php">
               <table class="w3-table w3-bordered">
                  <tr>
                     <td width="50%">
                        Name
                     </td>
                     <td>
                        <input type = "text" name = "username" class = "w3-input"/>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        USN
                     </td>
                     <td>
                        </label><input type = "password" name = "password" class = "w3-input" />
                     </td>
                  </tr>
               </table>
               <p align="center">
               <input type = "submit" value = " Submit " class="w3-btn w3-blue" />
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
               </p>
               <br>
            </form>
         </div>
         
      </div>
   </div>
   </div>
      
   </body>
</html>