<?php
	include 'db.php';

	$query_media = "SELECT * FROM media ORDER BY CASE WHEN top IN ('true','on') THEN 1 ELSE 0 END DESC, id DESC";
	$result_media = mysqli_query($mysqli, $query_media);
	$array_media = $result_media->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Blog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <style>
		main {padding: 50px;}

		.all-items {margin-top: 30px}

		.media-item {
			border: 1px solid;
			border-radius: 10px;
			margin-top: 20px;
			padding: 30px;
		}

		.media-item-first {
			background-color: blue; color: white
		}

		.media-item-first a{
			color: white
		}

		.media-item-main {
			background-color: white;
		}

		.media-item__title {
			font-size: 22px;
		}

		.media-item__desc {
			margin-top: 30px;
		}

		.button-wrap {margin-top: 30px; margin-bottom: 30px;}
		.button-wrap a {
			border: 1px solid gray;
			color: gray;
			padding: 10px 20px;
		}

		.media-item__link-title, .media-item__link-link {display: inline;}

		.media-logo {
			margin-top: 20px;
		}

		.media-item__img {
			width: 300px;
			height: 300px;
			flex-shrink: 0;
		}

		.row-main {
			display: flex;
			gap: 20px
		}

		.section-title-black {
			font-size: 30px;
			font-weight: 800;
		}
	</style>
</head>

<body class="page-media">

	<main class="" style="background-color: #F2EEEE;">
		<div class="container">
		    <center class="section-title-black">Media</center>
			<div class="all-items">
				<?php foreach ($array_media as $key => $value): ?>
					<?php
						$th_media = $value['media_link'];

						$query_link = "SELECT * FROM media_link WHERE media_link.link='$th_media'";
						$result_link = mysqli_query($mysqli, $query_link);
						$array_link = $result_link->fetch_all(MYSQLI_ASSOC);

						$media_logo = $array_link[0]['logo'];
						$is_white = ($array_link[0]['white'] == 1) ? 'logo-wrap' : '';

						$is_top = ($value['top'] == 'true' || $value['top'] == 'on') ? 'media-item-first' : '';
					?>

					<?php if ($value['top'] == 'true' || $value['top'] == 'on'): ?>
						<div class="media-item <?= $is_top ?>">
							<div class="row-main">
								<div class="media-item__txt">
									<div class="media-item__title pl-40"><?= $value['title'] ?></div>
									<div class="media-item__desc pl-40"><?= $value['description'] ?></div>
									<div class="media-item__bottom pl-40">
										<div class="button-link">
											<div class="button-wrap">
												<a target="_blank" class="media-item__button btn-coral" href="<?= $value['link'] ?>">
													<?= $value['button'] ?>
												</a>
											</div>
										</div>

										<div class="<?= $is_white ?> media-logo">
											<a href="<?= $th_media ?>" target="_blank">
												<img class="media-item__logo" src="img/media-link/<?= $media_logo ?>" alt="media-logo">
											</a>
										</div>
									</div>
								</div>

								<div>
									<div class="media-item__img" style="background-image: url(/img/media/<?= $value['img'] ?>?<?= time() ?>)">
									</div>

									<div class="pl-40 row-under-img">
										<div class="button-link">
											<div class="media-item__link">
												<div class="media-item__link-title"><?= $value['desc_link'] ?></div>
												<div class="media-item__link-link">
													<a href="<?= $th_media ?>" target="_blank"><?= $value['user_link'] ?></a>
												</div>
											</div>
										</div>
										<div class="<?= $is_white ?> media-logo">
											<a href="<?= $th_media ?>" target="_blank">
												<img class="media-item__logo" src="img/media-link/<?= $media_logo ?>" alt="media-logo">
											</a>
										</div>
									</div>
								</div>

							</div>
							<a class="big-link" target="_blank" href="<?= $value['link'] ?>"></a>
						</div>
					<?php else: ?>
					    <div class="media-item media-item-main <?= $is_top ?>">
							<div class="row-main">
								<div class="media-item__img" style="background-image: url(/img/media/<?= $value['img'] ?>?<?= time() ?>)">
								</div>

								<div class="media-item__txt">
									<div class="media-item__title pl-40"><?= $value['title'] ?></div>
									<div class="media-item__desc pl-40"><?= $value['description'] ?></div>

									<div class="media-item__bottom pl-40">
										<div class="button-link">
											<div class="button-wrap">
												<a target="_blank" class="media-item__button btn-coral" href="<?= $value['link'] ?>">
													<?= $value['button'] ?>
												</a>
											</div>
											<div class="media-item__link">
												<div class="media-item__link-title"><?= $value['desc_link'] ?></div>
												<div class="media-item__link-link">
													<a href="<?= $th_media ?>" target="_blank"><?= $value['user_link'] ?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<a class="big-link" target="_blank" href="<?= $value['link'] ?>"></a>
						</div>
					<?php endif; ?>
			    <?php endforeach; ?>
			</div>
		</div>
    </main>
</body>
</html>
