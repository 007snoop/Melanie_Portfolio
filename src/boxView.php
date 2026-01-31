<?php
function renderBox(array $box, bool $editable = false)
{
    $current = $box['size'] ?? '1x1';

    ?>
    <div class="grid-stack-item" data-id="<?= $box['id'] ?>" gs-x="<?= (int) ($box['grid_x'] ?? 0) ?>"
        gs-y="<?= (int) ($box['grid_y'] ?? 0) ?>" gs-w="<?= (int) ($box['grid_w'] ?? 1) ?>"
        gs-h="<?= (int) ($box['grid_h'] ?? 1) ?>">
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
function renderTextBox(array $layout, array $content, bool $editable)
{
    ?>
    <div class="grid-stack-item" data-id="<?= (int) $layout['id'] ?>" gs-x="<?= (int) $layout['grid_x'] ?>"
        gs-y="<?= (int) $layout['grid_y'] ?>" gs-w="<?= (int) $layout['grid_w'] ?>" gs-h="<?= (int) $layout['grid_h'] ?>">
        <div class="grid-stack-item-content">
            <?php if ($editable): ?>
                <button type="button" class="box-remove" title="Delete box">&#10006;</button>
                <form method='post' class="box-form">
                    <input type="hidden" name="id" value="<?= (int) $layout['id'] ?>">
                    <input type="hidden" name="type" value="text">
                    <input type="hidden" name="title" value="<?= htmlspecialchars($content['title']) ?? '' ?>">

                    <div class="box-content" contenteditable="true" data-field="content">
                        <?= htmlspecialchars($content['content']) ?? '' ?>
                    </div>

                    <button type="submit">Save</button>
                </form>

            <?php else: ?>
                
                <div class="box-content">
                    <?= nl2br(htmlspecialchars($content['content']) ?? '') ?>
                </div>

            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>

<?php
function renderLinkBox(array $layout, array $content, bool $editable)
{
    $id = (int) $layout['id'];
    $title = htmlspecialchars($content['title'] ?? 'Link');
    $url   = htmlspecialchars($content['url'] ?? '#');

    // ensure GridStack coordinates are always integers
    $x = (int) ($layout['grid_x'] ?? 0);
    $y = (int) ($layout['grid_y'] ?? 0);
    $w = (int) ($layout['grid_w'] ?? 1);
    $h = (int) ($layout['grid_h'] ?? 1);
    ?>
    <div class="grid-stack-item" data-id="<?= $id ?>" gs-x="<?= $x ?>" gs-y="<?= $y ?>" gs-w="<?= $w ?>" gs-h="<?= $h ?>">
        <div class="grid-stack-item-content <?= !$editable && empty($title) ? 'empty' : '' ?>">
            <?php if ($editable): ?>
                <button type="button" class="box-remove" title="Delete box">&#10006;</button>
                <form class="box-form">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="hidden" name="type" value="link">
                    <input type="hidden" name="title">
                    <input type="hidden" name="url">

                    <div contenteditable="true" class="title-content" data-field="title"><?= $title ?></div>
                    <div contenteditable="true" class="box-content" data-field="url"><?= $url ?></div>
                    <button type="submit">Save</button>
                </form>
            <?php else: ?>
                <a href="<?= $url ?>" target="_blank" rel="noopener noreferrer"><?= $title ?></a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
?>

<?php
function renderAddBoxButtons(): void
{
    ?>
    <div class="add-box-container">
        <button id="add-text-box">+ Add Text Box</button>
        <button id="add-link-box">+ Add Link Box</button>
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