<?php
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
	$limit = 25; // Only show 25 max

	$res = $pdo->run("SELECT torrents.id, torrents.name, torrents.image1, torrents.image2, categories.name as cat_name, categories.parent_cat as cat_parent FROM torrents LEFT JOIN categories ON torrents.category=categories.id WHERE banned = 'no' AND (image1 != '' OR image2 != '') AND visible = 'yes' ORDER BY id DESC LIMIT $limit");
	if ($res->rowCount() > 0) {
		begin_block(T_("LATEST_POSTERS"));

		print("<table align='center' cellpadding='0' cellspacing='0' width='100%' border='0'>");

		while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
				$cat = htmlspecialchars("$row[cat_parent] - $row[cat_name]");
				$name = htmlspecialchars($row["name"]);

				if ($row["image1"]) {
					print("<tr><td align='center'><a href='$site_config[SITEURL]/torrents/details?id=$row[id]' title='$name / $cat'><img border='0' src='$site_config[SITEURL]/uploads/images/$row[image1]' alt=\"$name / $cat\" width='100' /></a><br /></td></tr>");
				} else {
					print("<tr><td align='center'><a href='$site_config[SITEURL]/torrents/details?id=$row[id]' title='$name / $cat'><img border='0' src='$site_config[SITEURL]/uploads/images/$row[image2]' alt=\"$name / $cat\" width='100' /></a><br /></td></tr>");
				}
		}
		print("</table>");

		end_block();
	}
}
?>
