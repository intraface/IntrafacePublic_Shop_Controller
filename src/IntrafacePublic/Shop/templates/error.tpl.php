<?php if (isset($error) && is_array($error) && count($error) > 0): ?>
    <ul class="error-message">
    <?php foreach($error AS $item): ?>
        <li><?php e($item); ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
