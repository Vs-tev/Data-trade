<?php
require_once '../../init.php';

 if(@$_POST['query'] != '') {
        $filter = 'AND symbol_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';
    }else {
        $filter = '';
    }
    $getsymbol = new Symbol($filter);
    if(!empty($getsymbol->data())){
        foreach(@$getsymbol->data() as $row) {
            echo '<li><span class="element-li">'.$row->symbol_name.'</span><span class="symbol-description">'.$row->description.'<span><spann class="symbol-type">'.$row->type.'</span></li>';
        }
    }else {
        echo '<li>No results found..</li>';
    }