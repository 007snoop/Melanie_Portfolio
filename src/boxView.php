<?php
function renderBox(array $box, bool $editable = false)
{
    ?>
    <div class="bento-box <?= !$box['on_off'] ? 'disabled' : '' ?>" data-id="<?= $box['id'] ?>">
        <?php if ($editable): ?>
            <form method="post" class="box-form">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= $box['id'] ?>">

                <input
                    type="text"
                    name="title"
                    value="<?= htmlspecialchars($box['title']) ?>"
                    class="box-title-input"
                >

                <textarea
                    name="content"
                    class="box-content-input"
                ><?= htmlspecialchars($box['content']) ?></textarea>

                <input type="number" name="position" value="<?= $box['position'] ?>">

                <label>
                    Enabled
                    <input type="checkbox" name="on_off" <?= $box['on_off'] ? 'checked' : '' ?>>
                </label>

                <button type="submit">Save</button>
            </form>
        <?php else: ?>
            <h3><?= htmlspecialchars($box['title']) ?></h3>
            <div class="box-content">
                <?= nl2br(htmlspecialchars($box['content'])) ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}