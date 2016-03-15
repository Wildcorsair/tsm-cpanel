<div class="wrap">
    <h1>Models <a class="page-title-action" href="<?php echo get_permalink() . '?page=tsm-cpanel-model-edit'; ?>">Create new</a></h1>
    <table class="tsm-table">
      <tr>
        <th>ID</th>
        <th>Model</th>
        <th>Brand</th>
        <th>Visibility</th>
        <th>Price</th>
        <th></th>
        <th></th>
      </tr>
      <?php foreach ($rows as $row) { ?>
        <tr>
          <td><?php echo $row->id; ?></td>
          <td><?php echo $row->model_name; ?></td>
          <td><?php echo $row->manufacturer_name; ?></td>
          <td><?php echo ($row->visibility == 0) ? 'Visible' : 'Hidden'; ?></td>
          <td><?php echo $row->full_price; ?></td>
          <td class="control-container-cell">
              <a href="<?php echo get_permalink() . '?page=tsm-cpanel-model-edit&model_id=' . $row->id; ?>">Edit</a>
          </td>
          <td class="control-container-cell">
              <button class="btn-delete" data-value="<?php echo $row->id; ?>">Delete</button>
          </td>
        </tr>
      <?php
      } ?>
    </table>
</div>
<!-- Dialog window -->
<div id="dialog-confirm" title="Deleting">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;">    
        </span>Delete item?
    </p>
</div>