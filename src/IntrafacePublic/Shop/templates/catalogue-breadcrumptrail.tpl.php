<ul id="breadcrumptrail">
    <?php foreach($bread_crump as $item): ?>
        <li><?php if ($item['url'] == url()): ?><?php e($item['name']); ?><?php else: ?><a href="<?php e($item['url']); ?>"><?php e($item['name']); ?></a><?php endif; ?></li>
    <?php endforeach; ?>
</ul>
