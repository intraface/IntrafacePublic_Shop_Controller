<form method="get" action="<?php e(url()); ?>" id="shop-search">
    <fieldset>
        <legend><?php e(t('Search')); ?></legend>
        <input type="text" value="<?php e($search); ?>" name="q" />
        <input type="submit" value="<?php e(t('Search')); ?>" />
    </fieldset>
</form>
