<?php

    function create_directory($path){

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    /**
     * recursively create a long directory path
     */
    function create_dir($path) {
        if (is_dir($path)) return true;
        $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
        $return = create_dir($prev_path);
        return ($return && is_writable($prev_path)) ? mkdir($path) : false;
    }
