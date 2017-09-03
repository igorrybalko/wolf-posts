<?php
/*
Plugin Name: Wolf posts
Plugin URI: http://wdss.com.ua
Description: Posts list
Version: 1.0
Author: Rybalko Igor
Author URI: http://wdss.com.ua
*/

/*  Copyright 2017  Rybalko Igor  (email : igorrybalko2009@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class WolfPosts extends WP_Widget
{
    public function __construct() {
        parent::__construct("Posts", "Posts Widget",
            array("description" => "Show list posts"));
    }

    public function form($instance) {

        $title = $category = "";
        $quantity = 4;
        $tpl = 'default';

        foreach($instance as $k => $v){
            if($v){
                ${$k} = $v;
            }
        }

        $titleFieldId = $this->get_field_id("title");
        $titleFieldName = $this->get_field_name("title");
        echo '<p><label for="' . $titleFieldId . '">Title:</label><br>';
        echo '<input id="' . $titleFieldId . '" type="text" name="' 
        . $titleFieldName . '" value="' . $title . '"></p>';
    
        $catFieldId = $this->get_field_id("category");
        $catFieldName = $this->get_field_name("category");
        echo '<p><label for="' . $catFieldId . '">Category:</label><br>';
        echo '<select name="' . $catFieldName . '">';
        ?>

        <option value="" <?php selected( $category, '' )?>>All</option>
        <?php  foreach(get_categories() as $v){ ?>

            <option value="<?= $v->cat_ID ?>" <?php selected( $category, $v->cat_ID )?>><?= $v->cat_name ?></option>

        <?php  }
        echo '</select></p>';

        $quantityFieldId = $this->get_field_id("quantity");
        $quantityFieldName = $this->get_field_name("quantity");
        echo '<p><label for="' . $quantityFieldId . '">Quantity:</label><br>';
        echo '<input id="' . $quantityFieldId . '" type="number" name="' .
        $quantityFieldName . '" value="' . $quantity . '"></p>';

        $listFiles = scandir(__DIR__ . '/tpl');
        $listTpls = array_map(function($el){
            preg_match('/^tpl_(.*)\.php$/', $el, $matches);
                return $matches[1];
        }, $listFiles);
        $listTpls = array_filter($listTpls, function($el){
            return $el;
        });
        $tplFieldId = $this->get_field_id("tpl");
        $tplFieldName = $this->get_field_name("tpl");
        echo '<p><label for="' . $tplFieldId . '">Template:</label><br>';
        echo '<select name="' . $tplFieldName . '">';
        foreach($listTpls as $tplName){ ?>
            <option value="<?= $tplName ?>" <?php selected( $tpl, $tplName )?>><?= $tplName ?></option>
       <?php }

        echo '</select></p>';
    }

    public function update($newInstance, $oldInstance) {
        $values = array();
        $values["title"] = htmlentities($newInstance["title"]);
        $values["quantity"] = abs(intval($newInstance["quantity"]));
        $values["category"] = htmlentities($newInstance["category"]);
        $values["tpl"] = htmlentities($newInstance["tpl"]);
        return $values;
    }

    public function widget($args, $instance) {
        $title = $instance["title"];
        $quantity = $instance["quantity"];
        $catId = $instance["category"];
        $tpl = $instance["tpl"];
        $posts = get_posts([
                'category' => $catId,
                'numberposts' => $quantity,
        ]);

        require_once( __DIR__ . '/tpl/tpl_' . $tpl . '.php');
    }

}
add_action("widgets_init", function () {
    register_widget("WolfPosts");
});