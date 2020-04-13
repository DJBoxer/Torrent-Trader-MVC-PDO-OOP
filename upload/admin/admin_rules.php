<?php

if ($action=="rules" && $do=="view"){
	stdhead(T_("SITE_RULES_EDITOR"));
	navmenu();

	begin_frame(T_("SITE_RULES_EDITOR"));

	$res = SQL_Query_exec("SELECT * FROM rules ORDER BY id");

	print("<center><a href='admincp.php?action=rules&amp;do=addsect'>Add New Rules Section</a></center><br />\n");	

	while ($arr=mysqli_fetch_assoc($res)){
		
		print("<table width='100%' cellspacing='0' class='table_table'><tr>");
        print("<th class='table_head'>".$arr["title"]."</th>");
        print("</tr><tr><td class='table_col1'>");
        print("<form method='post' action='admincp.php?action=rules&amp;do=edit'>");
		print(format_comment($arr["text"]));
		print("</td></tr><tr><td class='table_head' align='center'><input type='hidden' value='$arr[id]' name='id' /><input type='submit' value='Edit' /></form>");
		print("</td>");
        print("</tr></table>");
        print("<br />");
	}
	end_frame();
	stdfoot();
}

if ($action=="rules" && $do=="edit"){

	if ($_GET["save"]=="1"){
		$id = (int)$_POST["id"];
		$title = sqlesc($_POST["title"]);
		$text = sqlesc($_POST["text"]);
		$public = sqlesc($_POST["public"]);
		$class = sqlesc($_POST["class"]);
		SQL_Query_exec("update rules set title=$title, text=$text, public=$public, class=$class where id=$id");
		write_log("Rules have been changed by ($CURUSER[username])");
		show_error_msg(T_("COMPLETE"), "Rules edited ok<br /><br /><a href='admincp.php?action=rules&amp;do=view'>Back To Rules</a>",1);
		die;
	}


	stdhead(T_("SITE_RULES_EDITOR"));
	navmenu();
	
	begin_frame("Edit Rule Section");
	$id = (int)$_POST["id"];
	$res = @mysqli_fetch_array(@SQL_Query_exec("select * from rules where id='$id'"));

	print("<form method=\"post\" action=\"admincp.php?action=rules&amp;do=edit&amp;save=1\">");
	print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\">\n");
	print("<tr><td>Section Title:</td><td><input style=\"width: 400px;\" type=\"text\" name=\"title\" value=\"$res[title]\" /></td></tr>\n");
	print("<tr><td style=\"vertical-align: top;\">Rules:</td><td><textarea cols=\"60\" rows=\"15\" name=\"text\">" . stripslashes($res["text"]) . "</textarea><br />NOTE: Remember that BB can be used (NO HTML)</td></tr>\n");

	print("<tr><td colspan=\"2\" align=\"center\"><input type=\"radio\" name='public' value=\"yes\" ".($res["public"]=="yes"?"checked='checked'":"")." />For everybody<input type=\"radio\" name='public' value=\"no\" ".($res["public"]=="no"?"checked='checked'":"")." />Members Only (Min User Class: <input type=\"text\" name='class' value=\"$res[class]\" size=\"1\" />)</td></tr>\n");
	print("<tr><td colspan=\"2\" align=\"center\"><input type=\"hidden\" value=\"$res[id]\" name=\"id\" /><input type=\"submit\" value=\"".T_("SAVE")."\" style=\"width: 60px;\" /></td></tr>\n");
	print("</table></form>");
	end_frame();
	stdfoot();
}

if ($action=="rules" && $do=="addsect"){

	if ($_GET["save"]=="1"){
		$title = sqlesc($_POST["title"]);
		$text = sqlesc($_POST["text"]);
		$public = sqlesc($_POST["public"]);
		$class = sqlesc($_POST["class"]);
		SQL_Query_exec("insert into rules (title, text, public, class) values($title, $text, $public, $class)");
		show_error_msg(T_("COMPLETE"), "New Section Added<br /><br /><a href='admincp.php?action=rules&amp;do=view'>Back To Rules</a>",1);
		die();
	}
	stdhead(T_("SITE_RULES_EDITOR"));
	navmenu();
	begin_frame(T_("ADD_NEW_RULES_SECTION"));
	print("<form method=\"post\" action=\"admincp.php?action=rules&amp;do=addsect&amp;save=1\">");
	print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\">\n");
	print("<tr><td>Section Title:</td><td><input style=\"width: 400px;\" type=\"text\" name=\"title\" /></td></tr>\n");
	print("<tr><td style=\"vertical-align: top;\">Rules:</td><td><textarea cols=\"60\" rows=\"15\" name=\"text\"></textarea><br />\n");
	print("<br />NOTE: Remember that BB can be used (NO HTML)</td></tr>\n");

	print("<tr><td colspan=\"2\" align=\"center\"><input type=\"radio\" name='public' value=\"yes\" checked=\"checked\" />For everybody<input type=\"radio\" name='public' value=\"no\" />&nbsp;Members Only - (Min User Class: <input type=\"text\" name='class' value=\"0\" size=\"1\" />)</td></tr>\n");
	print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Add\" style=\"width: 60px;\" /></td></tr>\n");
	print("</table></form>");
	end_frame();
	stdfoot();
}