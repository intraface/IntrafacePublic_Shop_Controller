
<fieldset id="details-customer-comment" class="details-customer">
    <legend><?php e(t('Comments')); ?></legend>

    <?php e(t('Your comments')); ?>:<br/>
    <textarea name="customer_comment"><?php if (isset($customer_comment)) e($customer_comment); ?></textarea>
</fieldset>
