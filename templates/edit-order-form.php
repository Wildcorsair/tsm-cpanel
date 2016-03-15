<form method="POST" action="<?php echo get_permalink() . '?page=tsm-cpanel-order-edit'; ?>">
  <div class="wrap">
    <h1><?php echo $caption; ?></h1>
    <div id="message" style="display:<?php echo $message_data['block_visibility']; ?>" 
         class="<?php echo $message_data['classes']; ?> updated is-dismissible">
        <p><?php echo $message_data['message']; ?></p>
    </div>

    <!-- Brand -->
    <div class="meta-row">
      <div class="meta-th">
        <label for="order-brand-id">Brand</label>
        <input type="hidden" name="order_id" value="<?php echo ( isset( $order->id ) ) ? $order->id : ''; ?>" />
      </div>
      <div class="meta-td">
        <select id="order-brand-id" name="brand_id">
            <?php foreach ( $manufacturers as $item ) {
                if ( isset( $order->id ) && $order->brand_id === $item->id ) {
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
        <label for="model-id">Model</label>
      </div>
      <div class="meta-td">
        <select id="model-id" name="model_id">
            <?php foreach ( $devices as $item ) {
                if ( isset( $order->id ) && $order->model_id === $item->id ) {
                    echo "<option selected value='{$item->id}|{$item->full_price}'>{$item->model_name}</option>";
                } else {
                    echo "<option value='{$item->id}|{$item->full_price}'>{$item->model_name}</option>";
                }
            } ?>
        </select>
      </div>
    </div>
    <!-- /Model -->
    
    <!-- Condition -->
    <div class="meta-row">
      <div class="meta-th">
        <label>Condition</label>
      </div>
      <div id="device-condition-controls" class="meta-td">
          <input id="perfect-device-condition" type="radio" name="condition" 
              <?php echo ( isset( $order->cond_percent ) 
                           && $order->cond_percent == 100 ) ? 'checked' : '' ?> value="100" />
          <label for="perfect-device-condition">Perfect</label>
          <input id="good-device-condition" type="radio" name="condition" 
              <?php echo ( isset( $order->cond_percent ) 
                           && $order->cond_percent == 75 ) ? 'checked' : '' ?> value="75" />
          <label for="good-device-condition">Good</label>
          <input id="fair-device-condition" type="radio" name="condition"
                 <?php echo ( isset( $order->cond_percent ) 
                           && $order->cond_percent == 50 ) ? 'checked' : '' ?> value="50" />
          <label for="fair-device-condition">Fair</label>
          <input id="dead-device-condition" type="radio" name="condition"
                 <?php echo ( isset( $order->cond_percent ) 
                           && $order->cond_percent == 25 ) ? 'checked' : '' ?> value="25" />
          <label for="dead-device-condition">Dead</label>
      </div>
    </div>
    <!-- /Condition -->
    
    <!-- Full price -->
    <div class="meta-row">
      <div class="meta-th">
        <label for="device-price">Device price</label>
      </div>
      <div class="meta-td">
          <input id="device-full-price" type="hidden" name="device_full_price" 
                 value="<?php echo ( isset( $order->device_full_price ) ) ? $order->device_full_price : $devices[0]->full_price; ?>" />
          <input id="device-price" type="text" name="device_price" readonly="readonly"
                 value="<?php echo ( isset( $order->device_price ) ) ? $order->device_price : $devices[0]->full_price; ?>" />
      </div>
    </div>
    <!-- /Full price -->
    
    <!-- E-mail -->
    <div class="meta-row">
      <div class="meta-th">
        <label for="user-email">User's email</label>
      </div>
      <div class="meta-td">
        <input id="user-email" type="text" name="user_email" 
               value="<?php echo ( isset( $order->user_email ) ) ? $order->user_email : ''; ?>" />
      </div>
    </div>
    <!-- /E-mail -->
    
    <!-- Status -->
    <div class="meta-row">
      <div class="meta-th">
        <label for="user-email">Order status</label>
      </div>
      <div class="meta-td">
        <select id="status-id" name="status_id">
            <option <?php echo ( isset( $order->order_status ) && $order->order_status == '0' ) ? 'selected' : ''; ?> value="0">Pending</option>
            <option <?php echo ( isset( $order->order_status ) && $order->order_status == '1' ) ? 'selected' : ''; ?> value="1">Completed</option>
        </select>
      </div>
    </div>
    <!-- /Status -->
    
    <div class="meta-row">
        <input type="submit" name="save_order" value="Save">
        <a href="<?php echo get_permalink() . '?page=tsm-all-orders'; ?>">Go Back</a>
    </div>
  </div>
</form>