<h1 id="category-name"><?php e($name); ?></h1>

<ul id="category-categories">
    <?php foreach($categories as $category): ?>
        <li><a href="<?php e(url('./'.$category['identifier'])); ?>"><?php e($category['name']); ?></a></li>
    <?php endforeach; ?>
</ul>
<div style="clear:both;"></div>
