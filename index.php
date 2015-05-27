<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-widht, user-scalable=no, initial-scale=1.0, maximun-scale=1.0, minimum-scale=1.0">
        <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
        <link rel="stylesheet" href="bs/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/ldap.css"/>
        <title></title>
    </head>
    <body>

        <br>
        <br>
        <br>
        <div class="container">
            <form action="http://localhost/LDAP_Pablo_Cristian/model.php" method="post">
            <div class="row center-block">
                <div class="col-md-offset-4  col-md-4 ">
                    <h1>LDAP PRACTICE</h1><br>
                    
                    <div class="form-group">
                        <label for="nick" center-block>login : </label>
                        <input type="text" id="nick" name="nick" class="form-control" placeholder="nick">
                    </div>
                    <div class="form-group">
                        <label for="password" >password : </label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="password">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" id="acceder">Acceder</button>
                    </div>
                </div>
            </div>
                </form>
        </div>
        

        <?php
        session_start();
        
        /**
         * Controlamos las variables de inicio de sesion y las destruimos si 
         * hace log out
         */
        if(isset($_GET['logout'])){
            if($_GET['logout']=="si"){
                session_destroy();
                $_SESSION['download'] = "no";

                }
            
        }
       
        
        ?>
    </body>
</html>
