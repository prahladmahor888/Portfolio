<?php
/**
 * Project Model
 * Handles portfolio projects data
 */

require_once __DIR__ . '/Model.php';

class Project extends Model {
    protected $table = 'projects';
    
    /**
     * Get featured projects
     * @param int $limit
     * @return array
     */
    public function getFeatured($limit = 6) {
        return $this->all(['is_featured' => 1, 'status' => 'active'], 'sort_order ASC', $limit);
    }
    
    /**
     * Get active projects
     * @param string $category Filter by category
     * @return array
     */
    public function getActive($category = null) {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active'";
        $params = [];
        
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY sort_order ASC, created_at DESC";
        
        return $this->query($sql, $params)->fetchAll();
    }
    
    /**
     * Get all categories
     * @return array
     */
    public function getCategories() {
        $stmt = $this->db->query("SELECT DISTINCT category FROM {$this->table} 
                                   WHERE category IS NOT NULL AND status = 'active' 
                                   ORDER BY category");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Search projects by tech stack
     * @param string $technology
     * @return array
     */
    public function searchByTech($technology) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'active' AND tech_stack LIKE ? 
                ORDER BY created_at DESC";
        return $this->query($sql, ["%{$technology}%"])->fetchAll();
    }
}
