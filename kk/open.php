<?php

namespace kk;

/**
 * 公开接口
 * @author zhanghailong
 */
function open($app) {
    
    $output = new \stdClass();

    if(isset($_GET["path"])) {
        
        $path = $_GET["path"];

        if($path == '_raml') {

            $items = array();
            
            foreach($app->tasks() as $task) {

                $items[] = $task;

            }
            
            $output->items = $items;

        } else if($path) {

            $inputData = array();

            if($_GET) {
                foreach($_GET as $key=>$value) {
                    $inputData[$key] = $value;
                }
            }

            if($_POST) {
                foreach($_POST as $key=>$value) {
                    $inputData[$key] = $value;
                }
            }

            $task = $app->newTask($path,$inputData);

            if($task) {

                try {
                    $app->handle($task);
                    $output->data = $task->getResult();
                    $output->errno = 200;
                }
                catch(\Exception $ex) {
                    $output->errno = $ex->getCode();
                    $output->errmsg = $ex->getMessage();
                }
                

            } else {
                $output->errno = '404';
                $output->errmsg = "Not Found Task";
            }

        } else {
            $output->errno = '404';
            $output->errmsg = "Not Found Task";
        }

    } else {
        $output->errno = '404';
        $output->errmsg = "Not Found Path";
    }
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($output);
}

