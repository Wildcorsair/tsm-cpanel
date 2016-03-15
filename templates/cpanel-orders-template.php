<div class="wrap">
<h1>Orders <a class="page-title-action" href="<?php echo get_permalink() . '?page=tsm-cpanel-order-edit'; ?>">Create new</a></h1>
    <table class="tsm-table">
      <tr>
        <th>ID</th>
        <th>Brand</th>
        <th>Model</th>
        <th>E-Mail</th>
        <th>Price</th>
        <th>Status</th>
        <th></th>
        <th></th>
      </tr>
      <?php foreach ($rows as $row) { ?>
        <tr>
          <td><?php echo $row->id; ?></td>
          <td><?php echo $row->manufacturer_name; ?></td>
          <td><?php echo $row->model_name; ?></td>
          <td><?php echo $row->user_email; ?></td>
          <td><?php echo $row->device_price; ?></td>
          <td><?php echo ($row->order_status == 0) ? 'Pending' : 'Complited'; ?></td>
          <td class="control-container-cell">
              <a href="<?php echo get_permalink() . '?page=tsm-cpanel-order-edit&order_id=' . $row->id; ?>">Edit</a>
          </td>
          <td class="control-container-cell">
              <button class="btn-delete" data-value="<?php echo $row->id; ?>">Delete</button>
          </td>
        </tr>
      <?php
      } ?>
    </table>
</div>

