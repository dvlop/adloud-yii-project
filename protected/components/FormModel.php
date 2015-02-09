<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 12.03.14
 * Time: 0:14
 */

class FormModel extends CFormModel {

    public $isNew = true;

    public function getCategories()
    {
        $categories = \models\Category::getInstance();
        $categories = $categories->getList();

        return $categories;
    }


    public function removeWrongOption()
    {
        if(!empty($this->categories) && is_array($this->categories) && !empty($this->categories[0])) {
            if($this->categories[0] == 'multiselect-all') {
                unset($this->categories[0]);
            }
        }
    }

    public function removeWrongOptionAdditional()
    {
        if(!empty($this->additionalCategories) && is_array($this->additionalCategories) && !empty($this->additionalCategories[0])) {
            if($this->additionalCategories[0] == 'multiselect-all') {
                unset($this->additionalCategories[0]);
            }
        }

    }
} 