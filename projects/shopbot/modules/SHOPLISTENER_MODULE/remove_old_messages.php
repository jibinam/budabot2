<?php

$dt = time() - Setting::get('shop_message_age');

$db->begin_transaction();

$sql = "DELETE FROM shopping_messages WHERE dt < ?";
$db->exec($sql, $dt);

$sql = "DELETE FROM shopping_items WHERE message_id NOT IN (SELECT id FROM shopping_messages)";
$db->exec($sql);

$db->commit();

?>