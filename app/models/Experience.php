<?php
require_once __DIR__ . '/Model.php';

class Experience extends Model {
    protected $table = 'experiences';
    
    public function getAllOrdered() {
        return $this->all([], 'sort_order ASC, year_range DESC');
    }
}
