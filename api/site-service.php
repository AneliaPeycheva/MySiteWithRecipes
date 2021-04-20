<?php

if (isset($_GET["action"])) {
    $action = $_GET['action'];
    if ($action == "get_recipes") {
        $string = file_get_contents("database.json");
        $json_a = json_decode($string, true);            
        $response['recipes'] = array();     
  
        for($x = 0; $x < count($json_a['recipes']); $x++){
            $temp = array();   
            $temp['uid'] = $json_a['recipes'][$x]['uid'];
            $temp['title'] = $json_a['recipes'][$x]['title'];
            $temp['description'] = $json_a['recipes'][$x]['description'];
            $temp['data'] = $json_a['recipes'][$x]['data']; 
            $temp['picture'] = $json_a['recipes'][$x]['picture']; 
            $temp['ingredients'] = $json_a['recipes'][$x]['ingredients'];     
            $temp['pictures'] = $json_a['recipes'][$x]['pictures'];
            $temp['preparation'] = $json_a['recipes'][$x]['preparation'];
            $temp['portion'] = $json_a['recipes'][$x]['portion'];
            $temp['difficulty'] = $json_a['recipes'][$x]['difficulty'];
            array_push($response['recipes'], $temp);           
        }
        
        echo json_encode($response);
      
    } else if ($action == "get_recipe") {                      
        $recipeId = $_GET['uid'];  

        $string = file_get_contents("database.json");
        $json_a = json_decode($string, true);            
        $response['recipe'] = array();  
               
        for($x = 0; $x < count($json_a['recipes']); $x++) {
            if ($json_a['recipes'][$x]['uid'] == $recipeId) {
                $response['recipe']['uid'] = $recipeId;
                $response['recipe']['title'] = $json_a['recipes'][$x]['title'];
                $response['recipe']['description'] = $json_a['recipes'][$x]['description'];
                $response['recipe']['data'] = $json_a['recipes'][$x]['data']; 
                $response['recipe']['picture'] = $json_a['recipes'][$x]['picture'];   
                $response['recipe']['ingredients'] = $json_a['recipes'][$x]['ingredients'];      
                $response['recipe']['pictures'] = $json_a['recipes'][$x]['pictures'];
                $response['recipe']['preparation'] = $json_a['recipes'][$x]['preparation'];
                $response['recipe']['portion'] = $json_a['recipes'][$x]['portion'];
                $response['recipe']['difficulty'] = $json_a['recipes'][$x]['difficulty'];
            }
        }        
    
        echo json_encode($response);
       
    } else if ($action == "get_current_page_recipes") {                      
        $page = $_GET['page'];  

        $string = file_get_contents("database.json");
        $json_a = json_decode($string, true);            
        $response['recipes'] = array();     
    
        for($x = $page*9 - 9; $x < count($json_a['recipes']) && $x < $page*9; $x++){
            $temp = array();   
            $temp['uid'] = $json_a['recipes'][$x]['uid'];
            $temp['title'] = $json_a['recipes'][$x]['title'];
            $temp['description'] = $json_a['recipes'][$x]['description'];
            $temp['data'] = $json_a['recipes'][$x]['data']; 
            $temp['picture'] = $json_a['recipes'][$x]['picture']; 
            $temp['ingredients'] = $json_a['recipes'][$x]['ingredients'];     
            $temp['pictures'] = $json_a['recipes'][$x]['pictures'];            
            array_push($response['recipes'], $temp);
        }
        $response['recipes_count'] = count($json_a['recipes']);
        echo json_encode($response);
       
    }  else if ($action == "get_newest_recipes") {
        $string = file_get_contents("database.json");
        $json_a = json_decode($string, true);            
        $response['newest'] = array();     
  
        for ($x = count($json_a['recipes']) - 1; $x > count($json_a['recipes']) - 7; $x--) {
            $temp = array();   
            $temp['uid'] = $json_a['recipes'][$x]['uid'];
            $temp['title'] = $json_a['recipes'][$x]['title'];
            $temp['description'] = $json_a['recipes'][$x]['description'];
            $temp['data'] = $json_a['recipes'][$x]['data']; 
            $temp['picture'] = $json_a['recipes'][$x]['picture']; 
            $temp['ingredients'] = $json_a['recipes'][$x]['ingredients'];     
            $temp['pictures'] = $json_a['recipes'][$x]['pictures'];
            array_push($response['newest'], $temp);
        }
        
        echo json_encode($response);

    } else if ($action == "find_recipes") {
        $keyLower = mb_strtolower($_GET['key'], 'UTF-8');      
        $page = $_GET['page'];    
        $string = file_get_contents("database.json");
        $json_a = json_decode($string, true);   
        $finded = 0;          
        $response['recipes'] = array();             

        for($x = 0; $x < count($json_a['recipes']); $x++) {
            
            $lowerTitle = mb_strtolower(strval($json_a['recipes'][$x]['title']), 'UTF-8');   

            if  (strpos($lowerTitle, $keyLower) !== false ) {      
                $finded++;
                if ( $finded  > ($page * 9) - 9  &&  $finded  <= $page * 9 ) {
                    $temp = array();   
                    $temp['uid'] = $json_a['recipes'][$x]['uid'];
                    $temp['title'] = $json_a['recipes'][$x]['title'];
                    $temp['description'] = $json_a['recipes'][$x]['description'];
                    $temp['data'] = $json_a['recipes'][$x]['data']; 
                    $temp['picture'] = $json_a['recipes'][$x]['picture']; 
                    $temp['ingredients'] = $json_a['recipes'][$x]['ingredients'];     
                    $temp['pictures'] = $json_a['recipes'][$x]['pictures'];
                    array_push($response['recipes'], $temp);     
                }                 
            }  
        }
        $response['recipes_count'] = $finded;
        
        echo json_encode($response);      
    } 
}


?>