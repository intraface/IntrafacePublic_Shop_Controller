<?php if ($product['currency'][$currency]['before_price_incl_vat'] != 0.00): ?><p class="before_price"><?php e($currency); ?> <?php $before_price = new Ilib_Variable_Float($product['currency'][$currency]['before_price_incl_vat']);  e($before_price->getAsLocal($context->document()->locale(), 2)); ?></p><?php endif; ?>
<p class="price"><?php e($currency); ?> <?php $price = new Ilib_Variable_Float($product['currency'][$currency]['price_incl_vat']); e($price->getAsLocal($context->document()->locale(), 2)); ?></p>

<?php if ($product['stock'] == 0 || $stock['for_sale'] > 0): ?>
    <p><form method="POST" class="buy" action="<?php e(url('add'));?>">
            <input type="submit" name="add_product_id" value="<?php e(t('Buy')); ?>" />
        </form>
    </p>
<?php else: ?>
    <p class="shop-sold-out"><?php e(t('Product is sold out')); ?>.</p>
<?php endif; ?>
