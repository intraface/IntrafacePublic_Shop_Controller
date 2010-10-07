<fieldset id="details-newsletter" class="details-customer">
    <legend><?php e(t('Newsletter')); ?></legend>
    
    <p><?php e(t('Do you want to sign up for the newsletter?')); ?></p>
    <div>
        <input type="checkbox" name="customer_newsletter" id="customer_newsletter" <?php if (isset($customer_newsletter)) echo ' checked="checked"'; ?>" />
        <label for="customer_newsletter"><?php e(t('Yes, I want to subscribe to the newsletter')); ?></label>
    </div>
</fieldset>