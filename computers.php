<?php
require_once 'db.php';
require_once 'header.php';

$msg = '';
$edit_comp = null;

// Add/Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_computer'])) {
    $comp_name = $_POST['comp_name'];
    $ip_address = $_POST['ip_address'];
    $id = $_POST['computer_id'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE computers SET comp_name=?, ip_address=? WHERE id=?");
        if ($stmt->execute([$comp_name, $ip_address, $id])) {
            $msg = "<div class='badge active mb-4'>Computer updated successfully!</div>";
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO computers (comp_name, ip_address) VALUES (?, ?)");
        if ($stmt->execute([$comp_name, $ip_address])) {
            $msg = "<div class='badge active mb-4'>Computer added successfully!</div>";
        }
    }
}

// Delete
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM computers WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: computers.php");
    exit();
}

// Edit Fetch
if (isset($_GET['edit'])) {
    $stmt = $conn->prepare("SELECT * FROM computers WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit_comp = $stmt->fetch();
}
?>

<div class="flex justify-between align-center mb-4">
    <h2>Manage Computers</h2>
</div>

<?= $msg ?>

<div class="glass-panel mb-4">
    <h3>
        <?= $edit_comp ? 'Update' : 'Add New' ?> Computer
    </h3>
    <form method="POST" class="mt-4" style="display:flex; gap:1rem; align-items:flex-end; flex-wrap:wrap;">
        <input type="hidden" name="computer_id" value="<?= $edit_comp['id'] ?? '' ?>">
        <div class="form-group" style="flex:1; margin-bottom:0;">
            <label>Computer Name / PC No</label>
            <input type="text" name="comp_name" class="form-control" required
                value="<?= htmlspecialchars($edit_comp['comp_name'] ?? '') ?>" placeholder="e.g. PC-01">
        </div>
        <div class="form-group" style="flex:1; margin-bottom:0;">
            <label>IP Address (Optional)</label>
            <input type="text" name="ip_address" class="form-control"
                value="<?= htmlspecialchars($edit_comp['ip_address'] ?? '') ?>" placeholder="192.168.1.10">
        </div>
        <button type="submit" name="save_computer" class="btn-neon" style="margin-bottom:0.2rem;">
            <?= $edit_comp ? '<i data-feather="edit-2"></i> Update' : '<i data-feather="plus"></i> Add PC' ?>
        </button>
        <?php if ($edit_comp): ?>
            <a href="computers.php" class="btn-neon btn-danger" style="margin-bottom:0.2rem;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="glass-panel">
    <h3>Computer List</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Computer Name</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM computers ORDER BY id DESC");
                $i = 1;
                while ($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td>
                            <?= $i++ ?>
                        </td>
                        <td><strong>
                                <?= htmlspecialchars($row['comp_name']) ?>
                            </strong></td>
                        <td>
                            <?= htmlspecialchars($row['ip_address']) ?>
                        </td>
                        <td><span class="badge active">
                                <?= $row['status'] ?>
                            </span></td>
                        <td>
                            <a href="computers.php?edit=<?= $row['id'] ?>" class="badge active"
                                style="margin-right:0.5rem;"><i data-feather="edit" style="width:14px; height:14px;"></i>
                                Edit</a>
                            <a href="computers.php?delete=<?= $row['id'] ?>"
                                onclick="return confirm('Delete this computer?');" class="badge inactive"><i
                                    data-feather="trash-2" style="width:14px; height:14px;"></i> Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'footer.php'; ?>