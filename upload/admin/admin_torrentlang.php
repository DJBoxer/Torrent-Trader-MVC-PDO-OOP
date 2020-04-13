<?php
if ($action=="torrentlangs" && $do=="view"){
	stdhead(T_("TORRENT_LANGUAGES"));
	navmenu();
	begin_frame(T_("TORRENT_LANGUAGES"));
	echo "<center><a href='admincp.php?action=torrentlangs&amp;do=add'><b>Add New Language</b></a></center><br />";

	print("<i>Please note that language image is optional</i><br /><br />");

	echo("<center><table width='650' class='table_table'><tr>");
	echo("<th width='10' class='table_head'><b>Sort</b></th><th class='table_head'><b>".T_("NAME")."</b></th><th class='table_head'><b>Image</b></th><th width='30' class='table_head'></th></tr>");
	$query = "SELECT * FROM torrentlang ORDER BY sort_index ASC";
	$sql = SQL_Query_exec($query);
	while ($row = mysqli_fetch_array($sql)) {
		$id = $row['id'];
		$name = $row['name'];
		$priority = $row['sort_index'];

		print("<tr><td class='table_col1' align='center'>$priority</td><td class='table_col2'>$name</td><td class='table_col1' width='50' align='center'>");
		if (isset($row["image"]) && $row["image"] != "")
			print("<img border=\"0\" src=\"" . $site_config['SITEURL'] . "/images/languages/" . $row["image"] . "\" alt=\"" . $row["name"] . "\" />");
		else
			print("-");	
		print("</td><td class='table_col1'><a href='admincp.php?action=torrentlangs&amp;do=edit&amp;id=$id'>[EDIT]</a> <a href='admincp.php?action=torrentlangs&amp;do=delete&amp;id=$id'>[DELETE]</a></td></tr>");
	}
	echo("</table></center>");
	end_frame();
	stdfoot();
}

if ($action=="torrentlangs" && $do=="view"){
	stdhead(T_("TORRENT_LANGUAGES"));
	navmenu();
	begin_frame(T_("TORRENT_LANGUAGES"));
	echo "<center><a href='admincp.php?action=torrentlangs&amp;do=add'><b>Add New Language</b></a></center><br />";

	print("<i>Please note that language image is optional</i><br /><br />");

	echo("<center><table width='650' class='table_table'><tr>");
	echo("<th width='10' class='table_head'><b>Sort</b></th><th class='table_head'><b>".T_("NAME")."</b></th><th class='table_head'><b>Image</b></th><th width='30' class='table_head'></th></tr>");
	$query = "SELECT * FROM torrentlang ORDER BY sort_index ASC";
	$sql = SQL_Query_exec($query);
	while ($row = mysqli_fetch_array($sql)) {
		$id = $row['id'];
		$name = $row['name'];
		$priority = $row['sort_index'];

		print("<tr><td class='table_col1' align='center'>$priority</td><td class='table_col2'>$name</td><td class='table_col1' width='50' align='center'>");
		if (isset($row["image"]) && $row["image"] != "")
			print("<img border=\"0\" src=\"" . $site_config['SITEURL'] . "/images/languages/" . $row["image"] . "\" alt=\"" . $row["name"] . "\" />");
		else
			print("-");	
		print("</td><td class='table_col1'><a href='admincp.php?action=torrentlangs&amp;do=edit&amp;id=$id'>[EDIT]</a> <a href='admincp.php?action=torrentlangs&amp;do=delete&amp;id=$id'>[DELETE]</a></td></tr>");
	}
	echo("</table></center>");
	end_frame();
	stdfoot();
}


if ($action=="torrentlangs" && $do=="edit"){
	stdhead(T_("TORRENT_LANG_MANAGEMENT"));
	navmenu();

	$id = (int)$_GET["id"];
	
	if (!is_valid_id($id))
		show_error_msg(T_("ERROR"),T_("INVALID_ID"),1);

	$res = SQL_Query_exec("SELECT * FROM torrentlang WHERE id=$id");

	if (mysqli_num_rows($res) != 1)
		show_error_msg(T_("ERROR"), "No Language with ID $id.",1);

	$arr = mysqli_fetch_array($res);

	if ($_GET["save"] == '1'){
  	
		$name = $_POST['name'];
		if ($name == "")
			show_error_msg(T_("ERROR"), "Language cat cannot be empty!",1);

		$sort_index = $_POST['sort_index'];
		$image = $_POST['image'];

		$name = sqlesc($name);
		$sort_index = sqlesc($sort_index);
		$image = sqlesc($image);

		SQL_Query_exec("UPDATE torrentlang SET name=$name, sort_index=$sort_index, image=$image WHERE id=$id");

		show_error_msg(T_("COMPLETED"),"Language was edited successfully.",0);

	} else {
		begin_frame("Edit Language");
		print("<form method='post' action='?action=torrentlangs&amp;do=edit&amp;id=$id&amp;save=1'>\n");
		print("<center><table border='0' cellspacing='0' cellpadding='5'>\n");
		print("<tr><td align='left'><b>Name: </b><input type='text' name='name' value=\"".$arr['name']."\" /></td></tr>\n");
		print("<tr><td align='left'><b>Sort: </b><input type='text' name='sort_index' value=\"".$arr['sort_index']."\" /></td></tr>\n");
		print("<tr><td align='left'><b>Image: </b><input type='text' name='image' value=\"".$arr['image']."\" /> single filename</td></tr>\n");
		print("<tr><td align='center'><input type='submit' value='".T_("SUBMIT")."' /></td></tr>\n");
		print("</table></center>\n");
		print("</form>\n");
        end_frame();
	}
	stdfoot();
}

if ($action=="torrentlangs" && $do=="delete"){
	stdhead(T_("TORRENT_LANG_MANAGEMENT"));
	navmenu();

	$id = (int)$_GET["id"];

	if ($_GET["sure"] == '1'){

		if (!is_valid_id($id))
			show_error_msg(T_("ERROR"),"Invalid Language item ID",1);

		$newlangid = (int) $_POST["newlangid"];

		SQL_Query_exec("UPDATE torrents SET torrentlang=$newlangid WHERE torrentlang=$id"); //move torrents to a new cat

		SQL_Query_exec("DELETE FROM torrentlang WHERE id=$id"); //delete old cat
		
		show_error_msg(T_("COMPLETED"),"Language Deleted OK",1);

	}else{
		begin_frame("Delete Language");
		print("<form method='post' action='?action=torrentlangs&amp;do=delete&amp;id=$id&amp;sure=1'>\n");
		print("<center><table border='0' cellspacing='0' cellpadding='5'>\n");
		print("<tr><td align='left'><b>Language ID to move all Languages To: </b><input type='text' name='newlangid' /> (Lang ID)</td></tr>\n");
		print("<tr><td align='center'><input type='submit' value='".T_("SUBMIT")."' /></td></tr>\n");
		print("</table></center>\n");
		print("</form>\n");
	}
	end_frame();
	stdfoot();
}

if ($action=="torrentlangs" && $do=="takeadd"){
  		$name = $_POST['name'];
		if ($name == "")
    		show_error_msg(T_("ERROR"), "Name cannot be empty!",1);

		$sort_index = $_POST['sort_index'];
		$image = $_POST['image'];

		$name = sqlesc($name);
		$sort_index = sqlesc($sort_index);
		$image = sqlesc($image);

	SQL_Query_exec("INSERT INTO torrentlang (name, sort_index, image) VALUES ($name, $sort_index, $image)");

	if (mysqli_affected_rows($GLOBALS["DBconnector"]) == 1)
		show_error_msg(T_("COMPLETED"),"Language was added successfully.",1);
	else
		show_error_msg(T_("ERROR"),"Unable to add Language",1);
}

if ($action=="torrentlangs" && $do=="add"){
	stdhead(T_("TORRENT_LANG_MANAGEMENT"));
	navmenu();

	begin_frame("Add Language");
	print("<center><form method='post' action='admincp.php'>\n");
	print("<input type='hidden' name='action' value='torrentlangs' />\n");
	print("<input type='hidden' name='do' value='takeadd' />\n");

	print("<table border='0' cellspacing='0' cellpadding='5'>\n");

	print("<tr><td align='left'><b>Name:</b> <input type='text' name='name' /></td></tr>\n");
	print("<tr><td align='left'><b>Sort:</b> <input type='text' name='sort_index' /></td></tr>\n");
	print("<tr><td align='left'><b>Image:</b> <input type='text' name='image' /></td></tr>\n");

	print("<tr><td colspan='2'><input type='submit' value='".T_("SUBMIT")."' /></td></tr>\n");

	print("</table></form><br /><br /></center>\n");
	end_frame();
	stdfoot();
}