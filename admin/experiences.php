<?php
require_once '../includes/auth_check.php';
require_once '../app/models/Experience.php';
require_once '../app/helpers/helpers.php';

$experienceModel = new Experience();
$editItem = null;

// Handle Delete
if (isset($_GET['delete'])) {
    $experienceModel->delete((int)$_GET['delete']);
    header('Location: experiences.php');
    exit;
}

// Handle Edit Fetch
if (isset($_GET['edit'])) {
    $item = $experienceModel->find((int)$_GET['edit']);
    if ($item) $editItem = $item;
}

// Handle Post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => sanitizeInput($_POST['title']),
        'company' => sanitizeInput($_POST['company']),
        'year_range' => sanitizeInput($_POST['year_range']),
        'description' => sanitizeInput($_POST['description']),
        'type' => sanitizeInput($_POST['type']),
        'sort_order' => (int)$_POST['sort_order']
    ];
    
    // Allow saving empty description if sanitization wiped it (but sanitization preserves string usually)
    // If empty string is desired, that's fine.
    
    if (!empty($_POST['id'])) {
        $experienceModel->update($_POST['id'], $data);
    } else {
        $experienceModel->create($data);
    }
    header('Location: experiences.php');
    exit;
}

$experiences = $experienceModel->getAllOrdered();
$title = "Manage Experience";
include '../includes/admin-header.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Experience & Education</h1>
        <a href="experiences.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New
        </a>
    </div>

    <div class="row">
        <!-- Form Section -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo $editItem ? 'Edit Item' : 'Add New Item'; ?></h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="experiences.php">
                        <?php if ($editItem): ?>
                            <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label>Title / Degree</label>
                            <input type="text" name="title" class="form-control" required value="<?php echo $editItem['title'] ?? ''; ?>" placeholder="e.g. Senior Developer">
                        </div>
                        
                        <div class="form-group">
                            <label>Company / Institute</label>
                            <input type="text" name="company" class="form-control" required value="<?php echo $editItem['company'] ?? ''; ?>" placeholder="e.g. Google">
                        </div>
                        
                        <div class="form-group">
                            <label>Year Range</label>
                            <input type="text" name="year_range" class="form-control" required value="<?php echo $editItem['year_range'] ?? ''; ?>" placeholder="e.g. 2020 - Present">
                        </div>
                        
                        <div class="form-group">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="work" <?php echo ($editItem['type'] ?? '') == 'work' ? 'selected' : ''; ?>>Work Experience</option>
                                <option value="education" <?php echo ($editItem['type'] ?? '') == 'education' ? 'selected' : ''; ?>>Education</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo $editItem['description'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="<?php echo $editItem['sort_order'] ?? 0; ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block"><?php echo $editItem ? 'Update' : 'Add'; ?></button>
                        <?php if ($editItem): ?>
                            <a href="experiences.php" class="btn btn-secondary btn-block">Cancel</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- List Section -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Experience List</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Company</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($experiences)): ?>
                                    <tr><td colspan="5" class="text-center">No experiences added yet.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($experiences as $item): ?>
                                    <tr>
                                        <td><?php echo $item['sort_order']; ?></td>
                                        <td>
                                            <strong><?php echo clean($item['title']); ?></strong><br>
                                            <small><?php echo clean($item['year_range']); ?></small>
                                        </td>
                                        <td><?php echo clean($item['company']); ?></td>
                                        <td><span class="badge badge-<?php echo $item['type'] == 'work' ? 'primary' : 'success'; ?>"><?php echo ucfirst($item['type']); ?></span></td>
                                        <td>
                                            <a href="experiences.php?edit=<?php echo $item['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                            <a href="experiences.php?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin-footer.php'; ?>
