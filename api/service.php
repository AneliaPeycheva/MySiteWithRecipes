<?php

error_reporting(E_ALL);

$servername = "sql-server";
$username = "root";
$password = "root";
$dbname = "recipes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
mysqli_set_charset($conn, 'utf-8');

if (isset($_GET["action"])) {
    $action = $_GET['action'];
     
    if ($action == "get_recipes") {     
        $response['recipes'] = array();  
        
        $sql_get_recipes = "SELECT * FROM recipes";
        $result_get_recipes =  $conn->query($sql_get_recipes);
        
        if (mysqli_num_rows($result_get_recipes) > 0) {     
            while ($recipe = $result_get_recipes -> fetch_assoc()) {             
                array_push($response['recipes'], $recipe);   
            }            
        } else {
            echo "0 results";
        }          
    

    }  else if ($action == "get_recipe") {                      
        $recipeId = $_GET['uid'];             
        $response['recipe'] = array();  
        $sql_get_recipes = "SELECT * FROM recipes WHERE `uid` =" . $recipeId;
        $result_get_recipes =  $conn->query($sql_get_recipes);

        if (mysqli_num_rows($result_get_recipes) > 0) {    

            while ($recipe = $result_get_recipes -> fetch_assoc()) {    
                $recipe['ingredients'] = json_decode($recipe['ingredients'], false);  
                $recipe['pictures'] = json_decode($recipe['pictures'], false);       
                array_push($response['recipe'], $recipe);   
            }    
             
        } else {
            echo "0 results";
        }    

    }  else if ($action == "get_newest_recipes") {
               
        $response['newest'] = array();     
  
        $sql_get_recipes = "SELECT * FROM recipes ORDER BY `uid` DESC LIMIT 6";
        $result_get_recipes =  $conn->query($sql_get_recipes);
        
        if (mysqli_num_rows($result_get_recipes) > 0) {     
            while ($recipe = $result_get_recipes -> fetch_assoc()) {             
                array_push($response['newest'], $recipe);   
            }            
        } else {
            echo "0 results";
        }         

    }  else if ($action == "get_current_page_recipes") {                     
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;    
        $response['recipes'] = array();     
        $num_recipes_on_page = 9;  
        $total_recipes = $conn->query('SELECT COUNT(*) FROM recipes')->fetch_row()[0];
        $count_all_pages = ceil($total_recipes / $num_results_on_page);
        $sql_get_recipes = "SELECT * FROM recipes LIMIT " . $num_recipes_on_page * ($page-1) . "," . $num_recipes_on_page ;
        $result_get_recipes =  $conn->query($sql_get_recipes);
        
        if (mysqli_num_rows($result_get_recipes) > 0) {     
            while ($recipe = $result_get_recipes -> fetch_assoc()) {             
                array_push($response['recipes'], $recipe);   
            }            
        } else {
            echo "0 results";
        }       
    
     
        $response['recipes_count'] =  $total_recipes;
        
       
    } else if ($action == "find_recipes") {       
        $keyLower = mb_strtolower($_GET['key'], 'UTF-8');     
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;     
        $response['recipes'] = array();   

        $sql_get_recipes = "SELECT COUNT(*) FROM `recipes` WHERE LOWER(title) LIKE '%$keyLower%'";   
        $result_get_recipes =  $conn->query($sql_get_recipes);
        $count_finded = $result_get_recipes -> num_rows;

        $num_recipes_on_page = 9;       
        
        $sql_get_recipes = "SELECT * FROM `recipes` WHERE LOWER(title) LIKE '%$keyLower%' LIMIT " . $num_recipes_on_page * ($page-1) . "," . $num_recipes_on_page;   
        $result_get_recipes =  $conn->query($sql_get_recipes);
       
                        
        if (mysqli_num_rows($result_get_recipes) > 0) {     
            while ($recipe = $result_get_recipes -> fetch_assoc()) {             
                array_push($response['recipes'], $recipe);   
            }            
        }   
        $response['recipes_count'] = $count_finded;
    } else if ($action == "find_recipes_by_ingredient") {  
        $keyLower = mb_strtolower($_GET['key'], 'UTF-8');     
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;         
        $response['recipes'] = array();   

        $sql_get_recipes = "SELECT COUNT(*) FROM `recipes` WHERE LOWER(ingredients) LIKE '%$keyLower%'";        
        $result_get_recipes =  $conn->query($sql_get_recipes);
        $count_finded = $result_get_recipes -> num_rows;
       
        $num_recipes_on_page = 9;       
        
        $sql_get_recipes = "SELECT * FROM `recipes` WHERE LOWER(ingredients) LIKE '%$keyLower%' LIMIT " . $num_recipes_on_page * ($page-1) . "," . $num_recipes_on_page;   
        $result_get_recipes =  $conn->query($sql_get_recipes);
       
                        
        if (mysqli_num_rows($result_get_recipes) > 0) {     
            while ($recipe = $result_get_recipes -> fetch_assoc()) {             
                array_push($response['recipes'], $recipe);   
            }            
        }   
        $response['recipes_count'] = $count_finded;
    }
   
}

echo json_encode($response);
$conn->close();

?>