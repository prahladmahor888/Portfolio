<?php
/**
 * Message Model
 * Handles contact form submissions
 */

require_once __DIR__ . '/Model.php';

class Message extends Model {
    protected $table = 'messages';
    
    /**
     * Get unread messages
     * @return array
     */
    public function getUnread() {
        return $this->all(['is_read' => 0], 'created_at DESC');
    }
    
    /**
     * Get recent messages
     * @param int $limit
     * @return array
     */
    public function getRecent($limit = 10) {
        return $this->all([], 'created_at DESC', $limit);
    }
    
    /**
     * Mark as read
     * @param int $id
     * @return bool
     */
    public function markAsRead($id) {
        return $this->update($id, ['is_read' => 1]);
    }
    
    /**
     * Count unread messages
     * @return int
     */
    public function countUnread() {
        return $this->count(['is_read' => 0]);
    }
}
