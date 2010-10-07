<div id="product">
    <?php if (isset($breadcrumptrail)) echo ($breadcrumptrail); ?>
    
    <h1><?php e($product['number']); ?> <?php e($product['name']); ?></h1>
    
    <div id="pictures">
        <?php if (isset($pictures) && is_string($pictures)) echo $pictures; ?>
    </div>
    
    <p><?php echo nl2br($product['description']); ?></p>
    
    <?php if (isset($message) && is_string($message)) echo $message; ?>
    
    <?php if ($product['has_variation']): ?>
        <?php echo $product_variation_buy; ?>
    <?php else: ?>
        <?php echo $product_buy; ?>
    <?php endif; ?>
    
    <?php if (isset($related_products) && is_string($related_products)) echo $related_products; ?>    
</div>
