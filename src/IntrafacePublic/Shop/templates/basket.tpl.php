<form action="<?php e(url('./')); ?>" method="post" id="cartform">
    <table id="basket">
        <caption><?php e(t('Basket (including vat)')); ?></caption>
        <thead>
            <tr>
                <th></th>
                <th><?php e(t('Name')); ?></th>
                <th><?php e(t('Quantity')); ?></th>
                <th><?php e(t('Amount')); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td class="help" colspan="2"><?php e(t('You can change the quantity of the products by writing a new quantity and update the basket.')); ?></td>
                <td class="total"><strong><?php e(t('Total')); ?></strong></td>
                <td class="total" style="text-align: right;"><?php e($currency); ?> <?php $total_price = new Ilib_Variable_Float($total_price[$currency]['incl_vat']); e($total_price->getAsLocal($context->document()->locale(), 2)); ?></td>
            </tr>
        </tfoot>
        <tbody>
        <?php $i = 0; ?>
        <?php foreach($items AS $item): ?>
            <tr>
                <td>
                    <?php if (!empty($item['pictures'][0])): ?>
                        <img src="<?php e($item['pictures'][0]['thumbnail']['file_uri']); ?>" alt="<?php e($item['name']); ?>" height="<?php e($item['pictures'][0]['thumbnail']['height']); ?>" width="<?php e($item['pictures'][0]['thumbnail']['width']); ?>" />
                    <?php endif; ?>
                </td>
                <td>
                <?php
                    e($item["name"]);
                ?>
                </td>
                <td>
                    <?php if (empty($item["basketevaluation_product"]) OR $item["basketevaluation_product"] == 0): ?>
                        <input type="hidden" name="items[<?php e($i); ?>][product_id]" value="<?php e($item["product_id"]); ?>" />
                        <input type="hidden" name="items[<?php e($i); ?>][product_variation_id]" value="<?php e($item["product_variation_id"]); ?>" />
                        <input type="text" name="items[<?php e($i); ?>][quantity]" size="2" value="<?php e($item["quantity"]); ?>" />
                        <?php $i++; ?>
                    <?php endif; ?>
                </td>
                <td style="text-align: right;"><?php e($currency); ?>&nbsp;<?php $totalprice_incl_vat = new Ilib_Variable_Float($item['currency'][$currency]["totalprice_incl_vat"]); e($totalprice_incl_vat->getAsLocal($context->document()->locale(), 2)); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
</table>

<p>
    <input name="update" value="<?php e(t('Update basket')); ?>" type="submit" />
</p>

<p>
    <a href="<?php e(url('../')); ?>"><?php e(t('Continue shopping')); ?></a>
    <a class="buy" href="<?php e(url('details')); ?>"><?php e(t('Checkout')); ?></a>
</p>
</form>