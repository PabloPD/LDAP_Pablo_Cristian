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
        <script type="text/javascript" src="js/ldap.js"></script>
        <link rel="stylesheet" href="bs/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/ldap.css"/>
        <title></title>
    </head>
    <body>
        
        

    <?php
   session_start();
    //$user = "user1@toca.cat";mandersen4
    //$password = "Platano123$";
   $nombres_grupo=Array();
   $usuarios_grupo = Array();
   $user_names_forgroup=Array();
   if(isset($_POST['nick'])) $_SESSION['nick']=$_POST['nick'];
   if(isset($_POST['password'])) $_SESSION['password']=$_POST['password'];
   if(!isset($_SESSION['download'])){
       $_SESSION['download']="no";
   }

    /**
     * Download datos del servidor
     * usuarios y grupos
     */
    if($_SESSION['download']=="no"){
        $grupos = get_groups();
        
        if(count($grupos)>0){
        
        foreach ($grupos as $g) {
            
            array_push($nombres_grupo, $g['name']);
            
        }
        
        foreach ($nombres_grupo as $g) {
            
            $user_names_forgroup[$g] = get_members_prueba($g);
            
        }
        
        $x= 0;
        foreach ($user_names_forgroup as $g) {
            
            $array = Array();
            
            foreach ($g as $v) {
                array_push($array, $v['name']);
            }
            $usuarios_grupo[$nombres_grupo[$x]]=$array;

            $x++;
        }
 
    }
    $_SESSION['nombres_grupo'] = $nombres_grupo;
    $_SESSION['usuarios_grupo'] = $usuarios_grupo;
    $_SESSION['user_names_forgroup'] = $user_names_forgroup;
    $_SESSION['download']="si";
    }

    echo '<div class="container">';
    
    echo '<div class="row">';
    echo '<div class="col-md-offset-4 col-md-4 text-center">';
    echo '<h1>LDAP PRACTICE</h1>';
    echo '</div>';
    echo '<div class="col-md-offset-2 col-md-2 text-center">';
    echo '<h2><a href="http://localhost/LDAP_Pablo_Cristian?logout=si">Go out</a></h2>';
    echo '</div>';
    echo '</div>';
    
    /**
     * Comprobamos si el usuario corresponde al grupo Sysops para mostrarlo todos
     */
    if(isset($_SESSION['usuarios_grupo']) & count($_SESSION['usuarios_grupo'])>0){
        
        echo '<div class="row">';
        echo '<div class="col-md-offset-4 col-md-4 text-center">';
        echo '<h3>Grupos</h3><br><br>';
        echo '</div>';
        echo '</div>'; 
        
        if(in_array($_SESSION['nick'], $_SESSION['usuarios_grupo']['sysops'])){

            foreach ($_SESSION['nombres_grupo'] as $n) {
            
            echo '<div class="row">';
            echo '<div class="col-md-offset-4 col-md-4 text-center">';
                echo '<div> <a name="'.$n.'" href="http://localhost/LDAP_Pablo_Cristian/model.php?grupo='.$n.'">'. $n .'</a></div><br>';
                echo '</div>';
                echo '</div>';
                
                if(isset($_GET['grupo'])){
                    
                    if($_GET['grupo']== $n){
                        
                        echo '<div class="row center-block">';
                        
                        foreach ($_SESSION['user_names_forgroup'][$n] as $name) {
                            echo '<div class="col-md-3 text-center separar">';
                            //echo $name;
                            print_r($name);
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<br><br>';
                    }
                }
        }
        
        echo '<br>';
    }
    else{
        
        foreach ($_SESSION['nombres_grupo'] as $n) {
            echo '<br>';

            if (in_array($_SESSION['nick'], $_SESSION['usuarios_grupo'][$n])) {
                
                echo '<div class="row">';
                echo '<div class="col-md-offset-4 col-md-4 text-center">';
                echo '<div> <a name="'.$n.'" href="http://localhost/LDAP_Pablo_Cristian/model.php?grupo='.$n.'">'. $n .'</a></div><br>';
                echo '</div>';
                echo '</div>';
                
                if(isset($_GET['grupo'])){
                    
                    if($_GET['grupo']== $n){
                        
                        echo '<div class="row center-block">';
                        
                        foreach ($_SESSION['user_names_forgroup'][$n] as $name) {
                            echo '<div class="col-md-3 text-center separar">';
                            //echo $name;
                            print_r($name);
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<br><br>';
                    }
                }
            }
            else{
                echo '<div class="row">';
                echo '<div class="col-md-offset-4 col-md-4 text-center">';
                echo '<div> <a name="'.$n.'" href="#">'. $n .'</a></div><br>';
                echo '</div>';
                echo '</div>';
            }

        }
        
    }
    }
    else{
        echo '<br><br>';
        echo '<div class="row">';
        echo '<div class="col-md-offset-4 col-md-4 text-center">';
        echo '<h4>ERROR DE USUARIO O PASSWORD</h4><br><br>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '<br><br>';

    echo '</div>';
     
    
    
    

    
    /**   metodos ************************/
    
    /**
     * 
     * @param type $group
     * @param type $inclusive
     * @return array
     * Devuelve los grupos del LDAP
     */
    function get_groups($group=FALSE,$inclusive=FALSE) {
    // Active Directory server
    $ldap_host = "52.24.210.244";
 
    // Active Directory DN
    $ldap_dn = "OU=ibadia,DC=toca,DC=cat";
 
    // Active Directory user
    $user = $_SESSION['nick'];
    $password = $_SESSION['password'];
    
    $keep = array(
        "name"
    );
 
    // Conexion al AD
    
    $ldap_connection = ldap_connect($ldap_host);
    
    if($ldap_connection){
        
        @$lapbind = ldap_bind($ldap_connection,$user,$password);
        
        if($lapbind){
            if($group) $query = "(&"; else $query = "";
 
 	$query .= "(&(objectClass=group))";
 
    // Search AD
    $results = ldap_search($ldap_connection,$ldap_dn,$query);
    $entries = ldap_get_entries($ldap_connection, $results);
 
    // Eliminamos el primer elemnto ( siempre esta vacio )
    array_shift($entries);
 
    $output = array(); // Declaramos array para guardar el resultado
 
    $i = 0; // Counter
    // Build output array
    foreach($entries as $u) {
        foreach($keep as $x) {
        	// Comprobamos por atributo
    		if(isset($u[$x][0])) $attrval = $u[$x][0]; else $attrval = NULL;
 
        	// añadimos el resultado al output final.
        	$output[$i][$x] = $attrval;
        }
        $i++;
    }
            
        }
        else {
            //echo 'ERROR DE CONEXION, volver a intentar';
            $output = array();
        }
    }
    else {
            //echo 'ERROR DE CONEXION, volver a intentar';
            $output = array();
        }
        //Cerramos la conexión.
        ldap_close($ldap_connection);
 	// Begin building query

    return $output;
}

    /**
     * 
     * @param type $group
     * @return type
     * Devuelve los usuarios del LDAP
     */
    function get_members_prueba($group) {

    // Active Directory server
    $ldap_host = "52.24.210.244";
    // Active Directory DN
    //$ldap_dn = "CN=Users,DC=toca,DC=cat";
    $ldap_dn = "OU=ibadia,DC=toca,DC=cat";
    // Domain, for purposes of constructing $user
 
    // Active Directory user
    $user = $_SESSION['nick'];
    $password = $_SESSION['password'];
    // User attributes we want to keep
    // List of User Object properties:
    $keep = array(
        "name",
        "samaccountname",
        "givenname",
        "sn",
        "postalcode",
        "st",
        "memberof"
    );
 
    // Connect to AD
    $ldap = ldap_connect($ldap_host) or die("Could not connect to LDAP");

    ldap_bind($ldap,$user,$password) or die("Could not bind to LDAP");
 
    // Begin building query
    
    $string = '(&(objectCategory=user)(memberOf=CN='.$group.',OU=ibadia,DC=toca,DC=cat))';
    $query = $string;

    @$results = ldap_search($ldap,$ldap_dn,$query,$keep);

    @$entries = ldap_get_entries($ldap, $results);
 
    // Remove first entry (it's always blank)
    array_shift($entries);
 
    $output = array(); // Declare the output array
 
    $i = 0; // Counter
    // Build output array
    foreach($entries as $u) {
        
        if(isset($u["memberof"][0])){
           
            if($u["memberof"][0]=='CN='.$group.',OU=ibadia,DC=toca,DC=cat'){
                $output[$i]['name'] = $u['name'][0];
                $output[$i]['samaccountname'] = $u['samaccountname'][0];
                $output[$i]['givenname'] = $u['givenname'][0];
                $output[$i]['sn'] = $u['sn'][0];
                $output[$i]['postalcode'] = $u['postalcode'][0];
                $output[$i]['st'] = $u['st'][0];
                $i++;
            }

        }

    }
 
    ldap_close($ldap);
    return $output;
}
    
        ?>
    </body>
</html>