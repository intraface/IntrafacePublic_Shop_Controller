<h1 id="category-name"><?php e($name); ?></h1>

<ul id="category-categories">
    <?php foreach($categories AS $category): ?>
        <li><a href="<?php e($this->url('./'.$category['identifier'])); ?>"><?php e($category['name']); ?></a></li>
    <?php endforeach; ?>
</ul>
<div style="clear:both;"></div>
