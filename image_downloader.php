<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define ("MAIN_DIR", dirname(__FILE__));
include (MAIN_DIR . "/classSimpleImage.php");
include (MAIN_DIR . "/config.php");
include ( MAIN_DIR . '/mysql.php');
$db = new SafeMysql(array('user' => $config['dbuser'], 'pass' => $config['dbpassword'],'db' => $config['dbname'],'host' => $config['dbhost'], 'charset' => 'utf8mb4'));

                ini_set('memory_limit', '256M');  
                $img = new SimpleImage();
                $base_dir = $config['base_path'];
                $images = $db->getAll('SELECT * FROM images WHERE downloaded=0');
                
                foreach ($images as $image){
                
                      var_dump($image);
                    $filename = basename($image['url']);
                    $re = '/^(.+?)(\?.*?)?(#.*)?$/m';
                    
                    preg_match_all($re, $filename, $matches);
                    $filename = $matches[1][0];
                    
                    echo $image['author'] . ', ' . $image["permlink"] . ', ' . $image['url'] . ' , filename: ' . $filename . " \n";
                try{
                    if (!is_null($filename)){ 
                        $path = $base_dir . 'buffer/' . $config['blockchain']['name'] . '-' . $image['author'] . '-' . $image["permlink"] . '-' . $filename;
                        echo $path . " \n";
                        file_put_contents($path, file_get_contents($image['url']));
                        
                        $img->load($base_dir . 'buffer/' . $config['blockchain']['name'] . '-' . $image['author'] . '-' . $image["permlink"] . '-' . $filename);
                        $img->resizeToWidth(250);
                        $img->save($base_dir . 'thumbs/' . $config['blockchain']['name'] . '-' . $image['author'] . '-' . $image["permlink"] . '-' . $filename);
                        unlink($base_dir . 'buffer/' . $config['blockchain']['name'] . '-' . $image['author'] . '-' . $image["permlink"] . '-' . $filename);
                        $db->query("UPDATE images SET downloaded = 1 WHERE permlink=?s AND author=?s", $image['permlink'], $image['author']);
       
                    }
         } catch (Exception $e){
            echo $e;
        }
                }
               
        