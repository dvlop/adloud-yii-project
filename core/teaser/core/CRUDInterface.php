<?php
/**
 * Created by t0m
 * Date: 02.02.14
 * Time: 18:20
 */

namespace core;


interface CRUDInterface {
    public function initById($id);
    public function getId();
    public function save();
    public function update();
    public function delete($id);
}