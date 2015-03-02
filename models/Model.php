<?php
/**
 * Created by IntelliJ IDEA.
 * User: pm
 * Date: 02.03.15
 * Time: 11:38
 * To change this template use File | Settings | File Templates.
 */

class Model {
    private static $db;

    public static function init(){
        self::$db = new PDO('mysql:host=localhost;dbname=yakabarva;charset=UTF8',
            'root', 'root',
            array(
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            )
        );
    }

    public static function saveColors($colors){
        if(!isset(self::$db))
            self::init();

        $color_counter = $description_counter = 0;
        $repeated_colors = array();

        foreach($colors as $group_name => &$group){

            foreach($group as $color_name => &$color){
                try{
                    $q = self::$db->prepare('SELECT id FROM colors WHERE CAST(red AS DECIMAL) = '. $color->r.' AND CAST(green AS DECIMAL) = '.$color->g.' AND CAST(blue AS DECIMAL) = '.$color->b);
                    $q->execute();
                    $result = $q->fetchAll();
                    $color_id = null;

                    self::$db->beginTransaction();

                    if(sizeof($result)){
                        $color_id = $result[0]['id'];
                        array_push($repeated_colors, $color_name);
                    }else{
                        $q = self::$db->prepare('INSERT INTO colors (red, green, blue) values ('.$color->r.','.$color->g.','.$color->b.')');
                        $q->execute();
                        $color_id = self::$db->lastInsertId();
                        $color_counter++;
                    }

                    $qstr = 'INSERT INTO descriptions (`color_id`, `lang`, `name`, `description`, `group`, `adnotation`) values (
                                :color_id,
                                \'pl\',
                                :color_name,
                                :descr,
                                :group_name,
                                :adnotation
                    )';

                    $description_counter++;

                    $q = self::$db->prepare($qstr);
                    $q->bindParam(':color_id', $color_id, PDO::PARAM_INT);
                    $q->bindParam(':color_name', $color_name, PDO::PARAM_STR);
                    $q->bindParam(':descr', $color->desc, PDO::PARAM_STR);
                    $q->bindParam(':group_name', $group_name, PDO::PARAM_STR);
                    $q->bindParam(':adnotation', $color->adn, PDO::PARAM_STR);

                    $q->execute();

                    self::$db->commit();
                }catch(Exception $e){
                    self::$db->rollback();
                    return $e->getMessage() . ' ' . $q->getQueryString();
                }
            }
        }
        return "success! colors: $color_counter, descriptions: $description_counter";
    }
}