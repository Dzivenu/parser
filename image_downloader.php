<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define ("MAIN_DIR", dirname(__FILE__));
include (MAIN_DIR . "/classSimpleImage.php");
include (MAIN_DIR . "/config.php");

                     
                $image = new SimpleImage();
                $base_dir = $config['base_path'];
                $file_dir = $base_dir . $config['blockchain']['name'] . '.txt';
                $file = file($file_dir);
                file_put_contents($file_dir, '');
            foreach ($file as $f){
                
                try{
                    $arr = json_decode($f, true);
                    
                    $filename = basename($arr['link']);
                    
                    $re = '/^(.+?)(\?.*?)?(#.*)?$/m';
                    
                    preg_match_all($re, $filename, $matches);
                    $filename = $matches[1][0];
                    echo $arr['author'] . ', ' . $arr["permlink"] . ', ' . $arr['link'] . ' , filename: ' . $filename . " \n";
                  
                    if (!is_null($filename)){ 
                        $path = $base_dir . '/buffer/' . $config['blockchain']['name'] . '-' . $arr['author'] . '-' . $arr["permlink"] . '-' . $filename;

                        file_put_contents($path, file_get_contents($arr['link']));

                        $image->load($base_dir . '/buffer/' . $config['blockchain']['name'] . '-' . $arr['author'] . '-' . $arr["permlink"] . '-' . $filename);
                        $image->resizeToWidth(120);
                        $image->save($base_dir . '/thumbs/' . $config['blockchain']['name'] . '-' . $arr['author'] . '-' . $arr["permlink"] . '-' . $filename);
                        unlink($base_dir . '/buffer/' . $config['blockchain']['name'] . '-' . $arr['author'] . '-' . $arr["permlink"] . '-' . $filename);
                    }
               
               
        } catch (Exception $e){
            echo $e;
        }
       }