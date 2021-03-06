<?php

    require_once('DB.php');
    require_once('HTTP.php');

    $action = $_GET['action'];

    // "GETTING" and "POSTTING" the data
    if($action == 'get' || $action == 'login' || $action == 'isExist' )
    {
        $arr = array();
        parse_str($_SERVER['QUERY_STRING'], $arr);
    }
    else
    {
        $arr = $_POST;

    }
    $array = array();

    // order the data in values
    foreach ($arr as $key => $value) 
    {
        switch ($key) 
        {            
            case 'table':
                $table = $value;
            break;

            case 'field':
                $field = $value;
            break;

            case 'data':
                $data = $value;
            break;
            
            default:
            $array[$key] = $value;
        }
    }

    // calling the DB functions according the action + validate empty data
    switch ($action) {
        case 'insert':

            // insert new row in DB by the data the user provide
            if(empty($table) || empty($array))
            {
                $msg = "The server could not understand the request due to invalid syntax.";
                return HTTP::create('400' , $msg);
                break;
            }

            $db = new DB();
            $msg = $db->insert($table, $array);
            $db->close();
            return HTTP::create('201' , $msg);
            break;
        
        case 'delete':

            // delete row in DB by the data the user provide
            if(empty($table) || empty($field) || empty($data))
            {
                $msg = "The server could not understand the request due to invalid syntax.";
                return HTTP::create('400' , $msg);
                break;
            }

            $db = new DB();
            $msg = $db->delete($table, $field, $data);
            $db->close();
            return HTTP::create('200' , $msg);
            break;
        
        case 'get':

            // get all or specific row get 
            if(empty($table)) 
            {
                $msg = "The server could not understand the request due to invalid syntax.";
                return HTTP::create('400' , $msg);
                break;
            }
            if( empty($field) && empty($data) )
            {
                $db = new DB();
                $msg = $db->get($table);
                $db->close();
                return HTTP::create('200' , $msg);
                break;
            }
            elseif( empty($field) || empty($data) )
            {
                $msg = "The server could not understand the request due to invalid syntax.";
                return HTTP::create('400' , $msg);
                break;
            }

            $db = new DB();
            $msg = $db->get($table, $field, $data);
            $db->close();
            return HTTP::create('200' , $msg);
            break;

        case 'login':

            // get all or specific row get 
            if(empty($table)) 
            {
                $msg = "The server could not understand the request due to invalid syntax.";
                return HTTP::create('400' , $msg);
                break;
            }
            foreach ($array as $key => $value) 
            {
                switch ($key)
                {
                    case 'username':
                        $username = $value;
                    break;
                    
                    case 'password':
                        $password = $value;
                    break;

                    default:
                    $array[$key] = $value;
                    break;
                }
            }

            $db = new DB();
            $msg = $db->login($table, $username, $password);
            $db->close();
            if(!$msg)
            {
                return HTTP::create('404' , 'not exist');
            }
            return HTTP::create('302' , 'exist');
            break;

        case 'isExist':

            // checking if exist in DB
            if(empty($table)) 
            {
                $msg = "The server could not understand the request due to invalid syntax.";
                return HTTP::create('400' , $msg);
                break;
            }

            $db = new DB();
            $msg = $db->isExist($table, $field, $data);
            $db->close();
            var_dump($msg);
            if(!$msg)
            {
                return HTTP::create('200' , 'not exist');
            }
            return HTTP::create('302' , 'exist');
            break;
        
        case 'update':

            // update row in DB by the data the user provide
            if(empty($table) || empty($field) || empty($data) || empty($array))
            {
                $msg = "The server could not understand the request due to invalid syntax.";
                return HTTP::create('400' , $msg);
                break;
            }

            $db = new DB();
            $msg = $db->update($table, $field, $data, $array);
            $db->close();
            return HTTP::create('200' , $msg);
            break;
            
        default:
            $msg = "The server could not understand the request due to invalid syntax.";
            return HTTP::create('400' , $msg);
            break;
      }
?>