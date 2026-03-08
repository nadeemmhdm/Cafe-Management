<?php
require_once 'db.php';
require_once 'header.php';

$msg = '';

// Add User
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $entry_id = uniqid('UID-');
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $computer_id = $_POST['computer_id'];

    $stmt = $conn->prepare("INSERT INTO users (entry_id, name, email, mobile, address, computer_id) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$entry_id, $name, $email, $mobile, $address, $computer_id])) {
        $msg = "<div class='badge active mb-4'>User Entry Created. ID: $entry_id</div>";
    }
}

// Update Out/Time
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $price = $_POST['price'];
    $remarks = $_POST['remarks'];

    $stmt = $conn->prepare("UPDATE users SET out_time = CURRENT_TIMESTAMP, price = ?, remarks = ? WHERE id = ?");
    if ($stmt->execute([$price, $remarks, $user_id])) {
        $msg = "<div class='badge active mb-4'>User Out-Time & Details Updated Successfully!</div>";
    }
}
?>

<div class="flex justify-between align-center mb-4">
    <h2>Manage Users</h2>
</div>
<?= $msg ?>

<!-- Form Section -->
<div class="glass-panel" style="margin-bottom:2rem;">
    <h3 class="mb-4">New User Entry</h3>
    <form method="POST" class="stats-grid" style="gap:1rem;">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required placeholder="John Doe">
        </div>
        <div class="form-group">
            <label>Email ID</label>
            <input type="email" name="email" class="form-control" placeholder="john@example.com">
        </div>
        <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" name="mobile" class="form-control" placeholder="1234567890">
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control" placeholder="City, Area">
        </div>
        <div class="form-group">
            <label>Assign Computer</label>
            <select name="computer_id" class="form-control" required style="background:var(--bg-color);">
                <option value="">Select PC...</option>
                <?php
                $stmt = $conn->query("SELECT id, comp_name FROM computers ORDER BY comp_name ASC");
                while ($c = $stmt->fetch())
                    echo "<option value='{$c['id']}'>{$c['comp_name']}</option>";
                ?>
            </select>
        </div>
        <div class="form-group" style="display:flex; align-items:flex-end;">
            <button type="submit" name="add_user" class="btn-neon w-100" style="width:100%;">
                <i data-feather="user-plus"></i> Add Entry
            </button>
        </div>
    </form>
</div>

<!-- Active Users List -->
<div class="glass-panel mb-4">
    <h3>Active Users (Currently Logged In)</h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Entry ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Computer</th>
                    <th>In Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT u.*, c.comp_name FROM users u LEFT JOIN computers c ON u.computer_id = c.id WHERE u.out_time IS NULL ORDER BY u.id DESC");
                while ($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td><strong>
                                <?= $row['entry_id'] ?>
                            </strong></td>
                        <td>
                            <?= $row['name'] ?>
                        </td>
                        <td>
                            <?= $row['mobile'] ?>
                        </td>
                        <td>
                            <?= $row['comp_name'] ?>
                        </td>
                        <td>
                            <?= $row['in_time'] ?>
                        </td>
                        <td>
                            <button onclick="document.getElementById('modal-<?= $row['id'] ?>').style.display='block'"
                                class="btn-neon" style="font-size:0.8rem; padding:0.4rem 0.8rem;">Checkout</button>
                        </td>
                    </tr>

                    <!-- Modal for Checkout -->
                    <div id="modal-<?= $row['id'] ?>" class="glass-panel"
                        style="display:none; position:fixed; top:20%; left:50%; transform:translateX(-50%); z-index:9999; box-shadow:0 0 50px rgba(0,0,0,0.8); width:90%; max-width:400px;">
                        <h3 class="mb-4">Update Out-Time & Price</h3>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <div class="form-group">
                                <label>Entry ID:
                                    <?= $row['entry_id'] ?>
                                </label>
                                <label>In Time:
                                    <?= $row['in_time'] ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Amount Charged (₹)</label>
                                <input type="number" step="0.01" name="price" class="form-control" required
                                    placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control" rows="2"
                                    placeholder="Time over..."></textarea>
                            </div>
                            <div class="flex" style="gap:1rem;">
                                <button type="submit" name="update_user" class="btn-neon w-100"
                                    style="flex:1;">Update</button>
                                <button type="button"
                                    onclick="document.getElementById('modal-<?= $row['id'] ?>').style.display='none'"
                                    class="btn-neon btn-danger" style="flex:1;">Cancel</button>
                            </div>
                        </form>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'footer.php'; ?>