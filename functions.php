<?php
/**
 * 
 *  Helper Functions.
 * 
 *
 *
 */

// TODO Refactor this so that null get vars arent included.

function eps_build_action_url($text, $action, $id)
{
    return sprintf('<a href="?page=%s&tab=%s&action=%s&id=%s">%s</a>',$_GET['page'],$_GET['tab'], $action, $id, $text);
}


?>