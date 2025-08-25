<?php
    session_start();
    $g_site_root = $_SERVER['DOCUMENT_ROOT'];
    $pathfile  = $g_site_root.'/img/media/';
	header('Content-Type: application/json; charset=utf-8');

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			http_response_code(403);
			die("Error CSRF: invalid request");
		}

		include_once 'db.php';

        $arr_par = array(
			'media_id', 'media_title', 'media_description', 'top_media',
			'media_link', 'media_desc_link', 'media_media_link', 'user_link', 'media_button'
		);

        foreach ($arr_par as $key => $value) {
			$$value = '';
			if (isset($_POST[$value])) {
				$$value = mysqli_real_escape_string($mysqli, (string)$_POST[$value]);
			}
		}

		if(is_numeric($media_id)) {   //edit by id
		    // path of existing img
			$q_by_id = "select * from media where id = '$media_id'";

			$result_by_id = mysqli_query($mysqli, $q_by_id);
			$array_by_id = $result_by_id->fetch_all(MYSQLI_ASSOC);

			$this_path = $pathfile.$array_by_id[0]['img'];

			$query = "UPDATE media set
					media.title = '$media_title',
					media.description = '$media_description',
					media.link = '$media_link',
					media.desc_link = '$media_desc_link',
					media.media_link = '$media_media_link',
					media.user_link = '$user_link',
					media.button = '$media_button',
					media.top = '$top_media'
					where media.id='$media_id' ";

			if ($array_by_id[0]['img'] == '') { // if img is empty, generate new
				$this_path0 = $media_id.'.webp';

				$this_path = $pathfile.$this_path0;

				$query4 = "UPDATE media set
						media.img = '$this_path0'
						where media.id='$media_id' ";

				$result4 = mysqli_query($mysqli, $query4);
			}

			$result = mysqli_query($mysqli, $query);

			if ($result) {
				$last_id = mysqli_insert_id($mysqli);
				echo json_encode([
					"status" => "ok",
					"id" => ''
				]);
			} else {
				echo json_encode([
					"status" => "error",
					"message" => mysqli_error($mysqli)
				]);
			}

		} else {  // add new
		    $generated_addr = round(microtime(true) * 1000);
			$this_path = $pathfile.$generated_addr.'.webp';  //???
			$path_img_db = $generated_addr.'.webp';

			$query = "INSERT INTO media
				(
					id,
					title,
					description,
					link,
					desc_link,
					media_link,
					user_link,
					button,
					top,
					img
				)
				VALUES
				(
					NULL,
					'$media_title',
					'$media_description',
					'$media_link',
					'$media_desc_link',
					'$media_media_link',
					'$user_link',
					'$media_button',
					'$top_media',
					'$path_img_db'
				)";

			$result = mysqli_query($mysqli, $query);

			if ($result) {
				$last_id = mysqli_insert_id($mysqli);
				echo json_encode([
					"status" => "ok",
					"id" => $last_id
				]);
			} else {
				echo json_encode([
					"status" => "error",
					"message" => mysqli_error($mysqli)
				]);
			}
		}

		if(isset($_FILES) && $_FILES['media_img']['error'] == 0) {
			$tmp = explode('.', $_FILES["media_img"]["name"]);
			$ext = end($tmp);

			if ($ext == 'webp') {
				if (file_exists($this_path)) {unlink($this_path);}
				move_uploaded_file($_FILES['media_img']['tmp_name'], $this_path);
			} else if ((isset($_POST['convert_canv_serv'])) && ($_POST['convert_canv_serv'] != '')) {
				if (file_exists($this_path)) {unlink($this_path);}
				$img = $_POST['convert_canv_serv'];
				$img = str_replace('data:image/webp;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$data = base64_decode($img);
				file_put_contents($this_path, $data);
			}
		}

		$mysqli -> close();
	}
?>
