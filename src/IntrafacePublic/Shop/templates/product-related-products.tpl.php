<?php if (!empty($related_products) AND is_array($related_products) AND count($related_products) > 0): ?>
    <h3><?php e(t('Related products')); ?></h3>
    <ul>
        <?php foreach($related_products as $value): ?>
            <li><a href="<?php e(url('../' . $value['id'])); ?>"><?php e($value['name']); ?></a></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>