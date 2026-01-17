<?php
/**
 * Setting Model
 * Handles site settings (key-value store)
 */

require_once __DIR__ . '/Model.php';

class Setting extends Model {
    protected $table = 'settings';
    private static $cache = [];
    
    /**
     * Get setting value by key
     * @param string $key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function get($key, $default = null) {
        // Check cache first
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        
        $stmt = $this->db->prepare("SELECT setting_value FROM {$this->table} WHERE setting_key = ?");
        $stmt->execute([$key]);
        $value = $stmt->fetchColumn();
        
        if ($value !== false) {
            self::$cache[$key] = $value;
            return $value;
        }
        
        return $default;
    }
    
    /**
     * Set setting value
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return bool
     */
    public function set($key, $value, $type = 'text') {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE setting_key = ?");
        $stmt->execute([$key]);
        $existing = $stmt->fetch();
        
        // Update cache
        self::$cache[$key] = $value;
        
        if ($existing) {
            // Update existing setting
            return $this->update($existing['id'], [
                'setting_value' => $value,
                'setting_type' => $type
            ]);
        } else {
            // Create new setting
            return $this->create([
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_type' => $type
            ]) !== false;
        }
    }
    
    /**
     * Get all settings as key-value array
     * @return array
     */
    public function getAll() {
        if (!empty(self::$cache)) {
            return self::$cache;
        }
        
        $stmt = $this->db->query("SELECT setting_key, setting_value FROM {$this->table}");
        $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        self::$cache = $settings;
        return $settings;
    }
    
    /**
     * Update multiple settings at once
     * @param array $settings Associative array of key => value
     * @return bool
     */
    public function updateMultiple($settings) {
        $success = true;
        
        foreach ($settings as $key => $value) {
            if (!$this->set($key, $value)) {
                $success = false;
            }
        }
        
        return $success;
    }
}
