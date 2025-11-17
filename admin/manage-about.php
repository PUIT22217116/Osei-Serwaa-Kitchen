<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
include 'header.php';

$dataFile = __DIR__ . '/../data/about.json';
$about = [];
if (file_exists($dataFile)) {
    $about = json_decode(file_get_contents($dataFile), true) ?: [];
}

// helpers to pull values safely
function v($k, $d = '') { global $about; return htmlspecialchars($about[$k] ?? $d); }
function deep($path, $d = '') { global $about; $parts = explode('.', $path); $cur = $about; foreach ($parts as $p) { if (!isset($cur[$p])) return $d; $cur = $cur[$p]; } return $cur; }
?>

<div class="admin-content">
    <div class="card">
        <div class="card-header">
            <h3>Edit About Page</h3>
            <div>
                <a href="dashboard.php" class="btn btn-outline">Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="save-about.php" method="post" enctype="multipart/form-data">
                <h4>Hero</h4>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="hero_title" class="form-control" value="<?php echo htmlspecialchars($about['hero']['title'] ?? 'Our Story'); ?>">
                </div>
                <div class="form-group">
                    <label>Subtitle</label>
                    <input type="text" name="hero_subtitle" class="form-control" value="<?php echo htmlspecialchars($about['hero']['subtitle'] ?? ''); ?>">
                </div>

                <h4>Story</h4>
                <div class="form-group">
                    <label>Story Title</label>
                    <input type="text" name="story_title" class="form-control" value="<?php echo htmlspecialchars($about['story']['title'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Story Paragraph</label>
                    <textarea name="story_paragraph" rows="6" class="form-control"><?php echo htmlspecialchars($about['story']['paragraph'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Story Image (leave blank to keep current)</label>
                    <?php if (!empty($about['story']['image'])): ?>
                        <div style="margin-bottom:.5rem"><img src="<?php echo htmlspecialchars($about['story']['image']); ?>" style="max-width:200px;border-radius:6px"></div>
                    <?php endif; ?>
                    <input type="file" name="story_image" accept="image/*">
                </div>

                <h4>Team Members</h4>
                <?php for ($i = 0; $i < 3; $i++):
                    $member = $about['team'][$i] ?? ['name'=>'','position'=>'','bio'=>'','image'=>''];
                ?>
                    <div class="form-group">
                        <label>Member <?php echo $i+1; ?> - Name</label>
                        <input type="text" name="team[<?php echo $i; ?>][name]" class="form-control" value="<?php echo htmlspecialchars($member['name']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <input type="text" name="team[<?php echo $i; ?>][position]" class="form-control" value="<?php echo htmlspecialchars($member['position']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Bio</label>
                        <textarea name="team[<?php echo $i; ?>][bio]" rows="3" class="form-control"><?php echo htmlspecialchars($member['bio']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Image (leave blank to keep current)</label>
                        <?php if (!empty($member['image'])): ?>
                            <div style="margin-bottom:.5rem"><img src="<?php echo htmlspecialchars($member['image']); ?>" style="max-width:120px;border-radius:6px"></div>
                        <?php endif; ?>
                        <input type="file" name="team_image_<?php echo $i; ?>" accept="image/*">
                    </div>
                    <hr>
                <?php endfor; ?>

                <h4>Values</h4>
                <?php for ($i = 0; $i < 4; $i++):
                    $val = $about['values'][$i] ?? ['icon'=>'','title'=>'','description'=>''];
                ?>
                    <div class="form-group">
                        <label>Icon (emoji or short text)</label>
                        <input type="text" name="values[<?php echo $i; ?>][icon]" class="form-control" value="<?php echo htmlspecialchars($val['icon']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="values[<?php echo $i; ?>][title]" class="form-control" value="<?php echo htmlspecialchars($val['title']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="values[<?php echo $i; ?>][description]" rows="2" class="form-control"><?php echo htmlspecialchars($val['description']); ?></textarea>
                    </div>
                <?php endfor; ?>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php';
?>
