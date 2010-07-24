<?php
/**
 * @var array $level_categories Containes the menu items for the level given by the key
 */
$level_categories = array(0 => $categories);

/**
 * @var array $level_menu_url Containes the identifier for each level until present level
 */
$level_menu_url[] = url('catalogue');

/**
 * @var integer level
 */
$level = 0;

# The first level is printed as default. If there is no categories it will fall through to the end UL tag.
echo '<ul class="level_'.$level.'">';

# When level is -1 it is time to end.
while($level >= 0) {
    foreach($level_categories[$level] AS $category) {
        # We remove the current item, as it should not be used if we return to the array after sub items
        array_shift($level_categories[$level]);

        # Print link. The link is printet as implode of all leves identifier until now.
        echo '<li><a href="'.implode('/', array_merge($level_menu_url, array($category['identifier']))).'">'.$category['name'].'</a></li>';

        # If there is subcategories to the category
        if(is_array($category['categories']) && count($category['categories']) > 0) {

            # We make the items for the next level the sub categories of this category
            $level_categories[$level+1] = $category['categories'];

            # We add the current identifier to the array of identifieres
            $level_menu_url[] = $category['identifier'];

            # We move to next level
            $level++;

            # And when we move to next level we print a new UL.
            echo '<ul class="level_'.$level.'">';

            # We break this foreach as we are ready to go next level_categories for new level.
            # Notice that the last code in the while loop will be executed anyway
            break;
        }
    }

    # If all elements for the level_categories for this level is gone, we move a level up.
    if(count($level_categories[$level]) == 0) {
        # First we remove the identifier from the level_menu_url
        array_pop($level_menu_url);

        # We end the UL
        echo '</ul>';

        # And we move a level up.
        $level--;
    }
}
?>