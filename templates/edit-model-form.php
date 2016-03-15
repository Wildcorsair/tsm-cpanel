<form method="POST" action="<?php echo get_permalink() . '?page=tsm-cpanel-model-edit'; ?>">
<div class="wrap">
    <h1><?php echo $caption; ?></h1>
    <div id="message" style="display:<?php echo $block_visibility; ?>" 
         class="<?php echo $classes; ?> updated is-dismissible">
        <p><?php echo $message; ?></p>
    </div>

    <!-- Brand -->
    <div class="meta-row">    
        <div class="meta-th">
            <label for="model-name">Brand</label>
        </div>
        <div class="meta-td">
            <select id="manufacturer" name="manufacturer">
                <?php foreach ( $manufacturers as $item ) {
                    if ( isset( $record->id ) && $record->brand_id === $item->id ) {
                        echo "<option selected value='{$item->id}'>{$item->manufacturer_name}</option>";
                    } else {
                        echo "<option value='{$item->id}'>{$item->manufacturer_name}</option>";
                    }
                } ?>
            </select>
        </div>
    </div>
    <!-- /Brand -->

    <!-- Model -->
    <div class="meta-row">
        <div class="meta-th">
            <label for="model-name">Model</label>
        </div>
        <div class="meta-td">
            <input type="hidden" id="model-id" name="model_id" value="<?php echo isset( $record->id ) ? $record->id : ''; ?>" />
            <input type="text" id="model-name" name="model_name" value="<?php echo isset( $record->model_name ) ? $record->model_name : ''; ?>" />
        </div>
    </div>
    <!-- /Model -->

    <!-- Price -->
    <div class="meta-row">    
        <div class="meta-th">
            <label for="model-price">Full price</label>
        </div>
        <div class="meta-td">
            <input type="text" id="model-price" name="full_price" value="<?php echo isset( $record->full_price ) ? $record->full_price : ''; ?>" />
        </div>
    </div>
    <!-- /Price -->
    
    <!-- Status -->
    <div class="meta-row">
      <div class="meta-th">
        <label for="user-email">Visibility</label>
      </div>
      <div class="meta-td">
        <select id="model-visibility" name="model_visibility">
            <option <?php echo ( isset ( $record->visibility ) && $record->visibility == 0) ? 'selected' : ''?> value="0">Visible</option>
            <option <?php echo ( isset ( $record->visibility ) && $record->visibility == 1) ? 'selected' : ''?> value="1">Hidden</option>
        </select>
      </div>
    </div>
    <!-- /Status -->
    
    <div class="meta-row">
        <input type="submit" name="save_model" value="Save">
        <a href="<?php echo get_permalink() . '?page=/tsm-cpanel/inc/tsm-models.php'; ?>">Go Back</a>
    </div>
</div>
</form>
