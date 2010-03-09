<?php if (isset($error) && is_array($error) && count($error) > 0): ?>
    <ul class="error-message">
    <?php foreach($error AS $error): ?>
        <li><?php e($error); ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
