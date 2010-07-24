<form method="get" action="<?php e(url()); ?>" id="shop-search">
    <fieldset>
        <legend><?php e(__('Search')); ?></legend>
        <input type="text" value="<?php e($search); ?>" name="q" />
        <input type="submit" value="<?php e(__('Search')); ?>" />
    </fieldset>
</form>
