<?php
/**
 * Skill Model
 * Handles skills data with categories
 */

require_once __DIR__ . '/Model.php';

class Skill extends Model {
    protected $table = 'skills';
    
    /**
     * Get active skills
     * @return array
     */
    public function getActive() {
        return $this->all(['status' => 'active'], 'sort_order ASC');
    }
    
    /**
     * Get skills grouped by category
     * @return array
     */
    public function getByCategory() {
        $skills = $this->getActive();
        $grouped = [];
        
        foreach ($skills as $skill) {
            $category = $skill['category'] ?? 'Other';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $skill;
        }
        
        return $grouped;
    }
    
    /**
     * Get all categories
     * @return array
     */
    public function getCategories() {
        $stmt = $this->db->query("SELECT DISTINCT category FROM {$this->table} 
                                   WHERE status = 'active' 
                                   ORDER BY category");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
