<?php
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

global $wpdb;
$table_manufacturers = $wpdb->prefix.'manufacturers';
$table_models        = $wpdb->prefix.'models';
$table_orders        = $wpdb->prefix.'orders';
$charset_collate     = "ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARACTER SET = {$wpdb->charset} COLLATE = {$wpdb->collate}";

if($wpdb->get_var("SHOW TABLES LIKE `{$table_manufacturers}`") != $table_manufacturers) {
    
    $sql = "CREATE TABLE {$table_manufacturers} (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `manufacturer_name` varchar(65) NOT NULL,
                PRIMARY KEY  (id)
            ) {$charset_collate};";
    dbDelta($sql);
    
    $dump = "INSERT INTO `{$table_manufacturers}` (`id`, `manufacturer_name`) VALUES
                (1, 'Acer'),
                (2, 'Apple'),
                (3, 'Asus'),
                (4, 'Dell'),
                (5, 'HP'),
                (6, 'Lenovo'),
                (7, 'Samsung'),
                (8, 'Sony'),
                (9, 'Toshiba'),
                (10, 'Vaio');";
    $wpdb->query($dump);
}

if($wpdb->get_var("SHOW TABLES LIKE `{$table_models}`") != $table_models) {
    
    $sql = "CREATE TABLE {$table_models} (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `model_name` varchar(255) NOT NULL,
                `brand_id` int(11) NOT NULL,
                `full_price` decimal(10,2) NOT NULL,
                `visibility` tinyint(1) NOT NULL,
                PRIMARY KEY  (`id`),
                CONSTRAINT `fk_manufModels` FOREIGN KEY (`brand_id`) REFERENCES {$table_manufacturers} (`id`)
            ) {$charset_collate};";
    dbDelta($sql);
}

if($wpdb->get_var("SHOW TABLES LIKE `{$table_orders}`") != $table_orders) {
    
    $sql = "CREATE TABLE {$table_orders} (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `brand_id` int(11) NOT NULL,
                `model_id` int(11) NOT NULL,
                `device_full_price` decimal(10,2) NOT NULL,
                `cond_percent` tinyint(6) NOT NULL,
                `device_price` decimal(10,2) NOT NULL,
                `user_email` varchar(45) NOT NULL,
                `order_status` tinyint(1) NOT NULL,
                PRIMARY KEY  (`id`),
                CONSTRAINT `fk_manufOrders` FOREIGN KEY (`brand_id`) REFERENCES {$table_manufacturers} (`id`),
                CONSTRAINT `fk_modelOrders` FOREIGN KEY (`model_id`) REFERENCES {$table_models} (`id`)
            ) {$charset_collate};";
    dbDelta($sql);
}