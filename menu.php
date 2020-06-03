<?php
require_once("menu_class.php");
$menu = new menu_class();

$button[] = array('name' => "点击扫码",
                  'type' => "scancode_push",
                  'key'  => "rselfmenu_0_1"
                  );
$button[] = array('name' => "志愿者",
                  'type' => "click",
                  'key'  => "register"
				  );
$menu->delete_menu();
$result = $menu->create_menu($button);
var_dump($result);
print_r($button);
function bytes_to_emoji($cp)
{
	if ($cp > 0x10000){       # 4 bytes
		return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
	}else if ($cp > 0x800){   # 3 bytes
		return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
	}else if ($cp > 0x80){    # 2 bytes
		return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
	}else{                    # 1 byte
		return chr($cp);
	}
}
?>