<?php
/**
 * Blog Model
 * Handles blog posts with SEO features
 */

require_once __DIR__ . '/Model.php';
require_once dirname(__DIR__) . '/helpers/helpers.php';

class Blog extends Model {
    protected $table = 'blogs';
    
    /**
     * Get published blogs
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getPublished($limit = 10, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'published' 
                ORDER BY created_at DESC LIMIT ? OFFSET ?";
        return $this->query($sql, [$limit, $offset])->fetchAll();
    }
    
    /**
     * Find blog by slug
     * @param string $slug
     * @return array|false
     */
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    /**
     * Create blog with auto-generated slug
     * @param array $data
     * @return int|false
     */
    public function createBlog($data) {
        // Generate slug from title if not provided
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }
        
        // Auto-generate meta tags if not provided
        if (empty($data['meta_title'])) {
            $data['meta_title'] = $data['title'];
        }
        if (empty($data['meta_description']) && !empty($data['excerpt'])) {
            $data['meta_description'] = $data['excerpt'];
        }
        
        return $this->create($data);
    }
    
    /**
     * Update blog with slug regeneration
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateBlog($id, $data) {
        // Regenerate slug if title changed
        if (!empty($data['title'])) {
            $current = $this->find($id);
            if ($current && $current['title'] !== $data['title']) {
                $data['slug'] = $this->generateUniqueSlug($data['title'], $id);
            }
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Generate unique slug
     * @param string $title
     * @param int $excludeId
     * @return string
     */
    private function generateUniqueSlug($title, $excludeId = null) {
        $slug = generateSlug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug exists
     * @param string $slug
     * @param int $excludeId
     * @return bool
     */
    private function slugExists($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE slug = ?";
        $params = [$slug];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Increment view count
     * @param int $id
     * @return bool
     */
    public function incrementViews($id) {
        $sql = "UPDATE {$this->table} SET views = views + 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Get recent blogs
     * @param int $limit
     * @return array
     */
    public function getRecent($limit = 5) {
        return $this->all(['status' => 'published'], 'created_at DESC', $limit);
    }
    
    /**
     * Get popular blogs by views
     * @param int $limit
     * @return array
     */
    public function getPopular($limit = 5) {
        return $this->all(['status' => 'published'], 'views DESC', $limit);
    }
    
    /**
     * Search blogs
     * @param string $keyword
     * @return array
     */
    public function search($keyword) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'published' AND (title LIKE ? OR content LIKE ?) 
                ORDER BY created_at DESC";
        return $this->query($sql, ["%{$keyword}%", "%{$keyword}%"])->fetchAll();
    }
}
