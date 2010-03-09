<fieldset id="terms-of-trade">
    <label for="accept_terms_of_trade">
        <input type="checkbox" name="accept_terms_of_trade" id="accept_terms_of_trade" value="1" />
        <?php if(isset($terms_url) && $terms_url != ''): ?>
            <?php e(__('Yes, I accept')); ?> <a href="<?php e($terms_url); ?>" target="_blank"><?php e(__('the terms of trade')); ?></a>?
        <?php else: ?>
            <?php e(__('Yes, I accept')); ?> <?php e(__('the terms of trade')); ?>
        <?php endif; ?>
    </label>

</fieldset>
