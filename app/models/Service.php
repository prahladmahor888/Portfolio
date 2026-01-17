<?php
/**
 * Service Model
 * Handles services/offerings data
 */

require_once __DIR__ . '/Model.php';

class Service extends Model {
    protected $table = 'services';
    
    /**
     * Get active services
     * @return array
     */
    public function getActive() {
        return $this->all(['status' => 'active'], 'sort_order ASC');
    }
}
