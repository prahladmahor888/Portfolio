<?php
/**
 * Admin - Manage Messages
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/app/helpers/helpers.php';
require_once dirname(__DIR__) . '/app/models/Message.php';

requireAuth();

$messageModel = new Message();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        setFlash('error', 'Invalid request');
        redirect($_SERVER['REQUEST_URI']);
    }
    
    if (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        if ($messageModel->delete($id)) {
            setFlash('success', 'Message deleted');
        }
    } elseif (isset($_POST['mark_read'])) {
        $id = (int)$_POST['id'];
        if ($messageModel->markAsRead($id)) {
            setFlash('success', 'Marked as read');
        }
    }
    redirect(ADMIN_URL . '/messages.php');
}

$messages = $messageModel->all([], 'created_at DESC');
$pageTitle = 'Messages';
include dirname(__DIR__) . '/includes/admin-header.php';
?>

<div class="page-header">
    <h1>Contact Messages</h1>
    <div class="stats">
        <?php
        $unread = $messageModel->countUnread();
        echo "<span class='badge'>{$unread} Unread</span>";
        ?>
    </div>
</div>

<div class="messages-container">
    <?php if (empty($messages)): ?>
        <p class="text-center text-muted">No messages yet.</p>
    <?php else: ?>
        <?php foreach ($messages as $msg): ?>
            <div class="message-card" style="background: white; border: 1px solid <?php echo $msg['is_read'] ? 'var(--admin-border)' : 'var(--admin-primary)'; ?>; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: var(--admin-shadow); display: flex; gap: 1.5rem; align-items: start;">
                <!-- Avatar -->
                <div style="width: 50px; height: 50px; background: var(--admin-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.1rem; flex-shrink: 0;">
                    <?php 
                    $initials = '';
                    $nameParts = explode(' ', $msg['name']);
                    foreach($nameParts as $part) {
                        $initials .= strtoupper(substr($part, 0, 1));
                    }
                    echo substr($initials, 0, 2);
                    ?>
                </div>

                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <div>
                            <h3 style="margin: 0; font-size: 1.1rem; color: var(--admin-text);"><?php echo clean($msg['name']); ?></h3>
                            <div style="color: var(--admin-text-light); font-size: 0.9rem; margin-top: 0.25rem;">
                                <?php echo clean($msg['email']); ?> • 
                                <span style="color: var(--admin-text-light);"><?php echo timeAgo($msg['created_at']); ?></span>
                            </div>
                        </div>
                        <?php if (!$msg['is_read']): ?>
                            <span class="badge badge-active">New Message</span>
                        <?php endif; ?>
                    </div>
                    
                    <div style="background: var(--admin-bg); padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                        <?php if ($msg['subject']): ?>
                            <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--admin-text);"><?php echo clean($msg['subject']); ?></div>
                        <?php endif; ?>
                        <div style="color: var(--admin-text); line-height: 1.6; white-space: pre-wrap;"><?php echo clean($msg['message']); ?></div>
                    </div>
                    
                    <div class="message-actions" style="display: flex; gap: 0.75rem; align-items: center;">
                        <a href="mailto:<?php echo clean($msg['email']); ?>" class="btn btn-outline btn-sm">
                            <i class="fas fa-reply"></i> Reply
                        </a>
                        
                        <?php if (!$msg['is_read']): ?>
                            <form method="POST" style="display:inline;">
                                <?php echo csrfField(); ?>
                                <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                                <button type="submit" name="mark_read" class="btn btn-primary btn-sm">
                                    <i class="fas fa-check"></i> Mark as Read
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this message?');">
                            <?php echo csrfField(); ?>
                            <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-outline btn-sm" style="color: #EF4444; border-color: #EF4444;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include dirname(__DIR__) . '/includes/admin-footer.php'; ?>
