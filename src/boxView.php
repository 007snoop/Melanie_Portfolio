<?php
function renderBox(array $box, bool $editable = false)
{
    $size = $box['size'] ?? '1x1';
    [$w, $h] = explode('x', $size);
    ?>
    <div class="bento-box <?= !$box['on_off'] ? 'disabled' : '' ?>" 
     style="--w: <?= (int)$w ?>; --h: <?= (int)$h ?>" 
     draggable="true" 
     data-id="<?= $box['id'] ?>">
        <?php if ($editable): ?>
            <form method="post" class="box-form">

                <select name="size" class="size-picker">
                    <?php
                    $current = $box['size'] ?? '1x1';
                    foreach (['1x1' => 'Small', '2x1' => 'Wide', '1x2' => 'Tall', '2x2' => 'Large'] as $val => $label):
                        ?>
                        <option value="<?= $val ?>" <?= $current === $val ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= $box['id'] ?>">

                <input type="hidden" name="title">
                <input type="hidden" name="content">

                <div class="title-content" contenteditable="true" data-field="title" placeholder="title">
                    <?= $box['title'] ?>
                </div>

                <div class="box-content" contenteditable='true' data-field="content">
                    <?= $box['content'] ?>
                </div>

                <input type="number" name="position" value="<?= $box['position'] ?>">

                <label>
                    Enabled
                    <input type="checkbox" name="on_off" <?= $box['on_off'] ? 'checked' : '' ?>>
                </label>
                <button type="submit">Save</button>
            </form>
            <?php renderDeleteButton($box); ?>
        <?php else: ?>
            <h3><?= htmlspecialchars($box['title']) ?></h3>
            <div class="box-content">
                <?= nl2br(htmlspecialchars($box['content'])) ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>

<?php
function renderAddBoxForm(): void
{
    ?>
    <div class="add-box-container">
        <button id="show-add-box">+ Add New Box</button>
        <div id="add-box-form" style="display:none; margin-top:1em;">
            <form method="post">
                <input type="hidden" name="action" value="add">
                <input type="text" name="title" placeholder="Box title" required><br>
                <textarea name="content" placeholder="Box content" required></textarea><br>
                <input type="number" name="position" placeholder="Position" value="0" required><br>
                <button type="button" id="cancel-add-box">Cancel</button>
                <button type="submit">Add Box</button>
            </form>
        </div>
    </div>
    <?php
}
?>

<?php
function renderDeleteButton(array $box): void
{
    ?>
    <form method="post" style='display:inline;'>
        <input type="hidden" name='action' value='delete'>
        <input type="hidden" name='id' value="<?= $box['id'] ?>">
        <button type="submit" onclick="return confirm('Delete this box?')">Delete</button>
    </form>
    <?php
}
?>