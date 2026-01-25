<?php
function renderBox(array $box, bool $editable = false)
{
    $current = $box['size'] ?? '1x1';

    ?>
    <div class="grid-stack-item" 
    data-id="<?= $box['id'] ?>" 
    gs-x="<?= (int)($box['grid_x'] ?? 0) ?>" 
    gs-y="<?= (int)($box['grid_y'] ?? 0)?>" 
    gs-w="<?= (int)($box['grid_w'] ?? 1) ?>" 
    gs-h="<?= (int)($box['grid_h'] ?? 1) ?>">
        <div class="grid-stack-item-content <?= !$box['on_off'] ? 'disabled' : '' ?>">

            <?php if ($editable): ?>


                <form method="post" class="box-form">

                    <!-- Hidden fields for form submission -->
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $box['id'] ?>">
                    <input type="hidden" name="title">
                    <input type="hidden" name="content">
                    <input type="hidden" name="size" value="<?= $current ?>">

                    <!-- Title and content editable -->
                    <div class="title-content" contenteditable="true" data-field="title">
                        <?= htmlspecialchars($box['title']) ?>
                    </div>
                    <div class="box-content" contenteditable="true" data-field="content">
                        <?= htmlspecialchars($box['content']) ?>
                    </div>

                    <!-- Position and on/off -->
                    <label>
                        Enabled
                        <input type="checkbox" name="on_off" <?= $box['on_off'] ? 'checked' : '' ?>>
                    </label>
                    <br>
                    <br>
                    <button type="submit">Save</button>

                </form>
                <?php renderDeleteButton($box); ?>
            <?php else: ?>
                <h3><?= htmlspecialchars($box['title']) ?></h3>
                <div class="box-content">
                    <?= nl2br(htmlspecialchars($box['content'], ENT_QUOTES | ENT_SUBSTITUTE)) ?>
                </div>
            <?php endif; ?>
        </div>
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