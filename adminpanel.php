<?php
	session_start();
	if ((!$_SESSION['admin'])) {  // autorisation
		header('Location: adminavt.php');
		exit;
	}	
	
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
	}
	$csrf_token = $_SESSION['csrf_token'];

	include 'db.php';	
	
	$table = 'media';
	
	$query_media = "Select * From $table ORDER BY id";
	$result_media = mysqli_query($mysqli, $query_media);
	$array_media = $result_media->fetch_all(MYSQLI_ASSOC);
	
	$query_media_link = "Select *	From media_link	ORDER BY id";
	$result_media_link = mysqli_query($mysqli, $query_media_link);
	$array_media_link = $result_media_link->fetch_all(MYSQLI_ASSOC);	
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Adminpanel</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
	
	<style>
	    body {padding-top: 30px; margin-bottom: 90px}
		
		.container {
			margin: 0 auto;
			max-width: 1000px;
		}

		.item-media {margin-top: 20px}
		
		input[type=checkbox] {margin: 0px 4px 0 0px; transform: translateY(2px);}
		
		.margin-top-20 {margin-top: 20px}
		
		.gap-10 {gap: 10px}
		
		.row-flex {
			display: -webkit-box;
			display: flex;
			display: -webkit-flex;
			display: -ms-flex;
		}		
		
		.hide {display: none}
		
		.safari-warn {color: red}		
		
		summary {display: revert !important;} 
		
		.offset-30 {padding-left: 30px;}	
		
		.case-name {font-size: 16px;}		
		
		.item-media {border: 1px solid rgb(0 0 0 / 50%); padding: 10px;}	
	
		.update-status {display: inline-block; margin-left: 10px}

		textarea, input[type="text"], select {font-weight: 300;}
		
		.media-opd {
			border: 1px solid;
		}
		
		.img-demo {
			margin-top: 10px; width: 180px;
		}
		
		canvas {margin-top: 10px; width: 1px}	
		
		.media-img {margin-top: 10px;}
		
		input[type="text"] {margin-left: 5px}
		
		input[type=file] {display: block;}
	</style>
</head>
<body>	    
	<div class="container">	

		<?php foreach ($array_media as $key => $value): ?>
			<?php 
				$media_id   = $value['id'];	
				$media_link = $value['media_link'];	
				$is_top     = ($value['top'] == 'true' || $value['top'] == 'on') ? 'checked' : '';
			?>

			<details class="item-media">
				<summary class="case-name">
					<b><?= $value['title'] ?></b>
				</summary>

				<div class="offset-30">
					<form 
						id="media-<?= $media_id ?>" 
						action="javascript:postMedia(<?= $media_id ?>)" 
						enctype="multipart/form-data" 
						method="post"
						onsubmit="return prepareImgW(this);"
					>		

					    <input type="hidden" name="csrf_token" class="csrf_token" value="<?= $csrf_token ?>">

						<input class="media_id" type="hidden" name="media_id" value="<?= $media_id ?>">
						
						<label class="margin-top-20">
							<input <?= $is_top ?> class="top-publ" type="checkbox" name="top_media"><i>Highlight publication</i>
						</label>
						
						<div class="margin-top-20">
							<label class="row-flex gap-10">
								<b>Title</b>
								<textarea cols="50" rows="3" name="media_title" required><?= $value['title'] ?></textarea>
							</label>
						</div>	
						
						<div class="margin-top-20">
							<label class="row-flex gap-10">
								<b>Description</b>
								<textarea cols="70" rows="6" name="media_description"><?= $value['description'] ?></textarea>
							</label>
						</div>
						
						<div class="margin-top-20">
							<label> 
								<b>Button text</b>
								<input type="text" size="40" name="media_button" value="<?= $value['button'] ?>">
							</label>
						</div>
						
						<div class="margin-top-20">
							<label> 
								<b>Link to the article</b>
								<input type="text" size="60" name="media_link" value="<?= $value['link'] ?>">
							</label>
						</div>							

						<div class="margin-top-20">
						    <label> 
								<b>Link to the media</b>
								<select name="media_media_link" style="margin-left:5px">
									<?php foreach ($array_media_link as $key_l => $value_l): ?>
										<?php $sel = ($value_l['link'] == $media_link) ? 'selected' : ''; ?>
										<option <?= $sel ?> value="<?= $value_l['link'] ?>"><?= $value_l['link'] ?></option>
									<?php endforeach; ?>
								</select>
							</label>	
						</div>

						<div class="margin-top-20">
						    <label> 
								<b>Media link description</b>
								<input size="60" type="text" name="media_desc_link" value="<?= $value['desc_link'] ?>">
							</label> 	
						</div>

						<div class="margin-top-20">
						    <label> 
								<b>Author link</b>
								<input size="60" type="text" name="user_link" value="<?= $value['user_link'] ?>">
							</label> 	
						</div>

						<div class="safari-warn"></div>

						<div class="convert-webp margin-top-20">
							<b>Image</b>
							
							<input class="media-img load_image li_wp" name="media_img" type="file">
							
							<img class="img-demo" src="img/media/<?= $value['img'] ?>?<?= time() ?>" alt="No image uploaded">

							<canvas></canvas>
							<input class="convert-canv" name="convert_canv_serv" type="hidden" value="">
						</div>	

						<br>

						<input class="btn media-opd" type="submit" value="Save changes">		
						<div class="update-status"></div>
					</form>
				</div>	
			</details>
		<?php endforeach; ?>		
						
		<button style="margin-top: 30px" id="add-new-media">Add new post</button>					
						
	</div>		
<script>
    // When browser is Safari, converter to .webp not works
    var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    let saf_warn = $('.safari-warn');
  	if(isSafari) {saf_warn.text("Only .webp format is allowed")}

	/*************************add new article*************************/    
	
	$('#add-new-media').click(function(){
	    const ct = Date.now();
		let case_last = $('.item-media').last();
		let case_teml = case_last.clone();		
		case_teml.find('form').attr('id', "media-"+ct);
		case_teml.find('form').attr('action', "javascript:postMedia("+ct+")");		
		case_teml.find('input').not('.csrf_token').val('');
		case_teml.find('.top-publ').prop({'checked': false});
		case_teml.find('.media_id').val('null');
	    case_teml.find('canvas').css({'width': '1px'});
		case_teml.find('textarea').val('');	
		case_teml.find('img').remove();
		case_teml.find('.case-name b').text('New post');
		case_teml.find('.media-opd').val('Add post');
		case_teml.find('select[name="media_media_link"]').val('https://www.bbc.com//');
		case_teml.find('.update-status').text('');		
	
		let _this = $(this);
		_this.before(case_teml);		
	});		
	
	/*************************add new article*************************/	
	
	/*************************displaying uploaded images canvas*************************/
	
    $(document).on('change', '.load_image', handleImage);

	function handleImage(e) {
		var reader = new FileReader;
		var _this = this;
		
		var files = e.target.files;
  		var extension = files[0].type;
		
		if (!extension.startsWith('image/')) {
			alert('Only images are allowed (webp, jpg, png etc.)');
			_this.value = ''; 
			return;
		}
		
		if((isSafari) &&  (_this.classList.contains('li_wp'))) {
  			if (extension=='image/webp') {
  				//do nothing
  			} else {
  				alert('Only .webp format is allowed');
  				_this.val('');
  			}
  		}
		
		var loadImage = _this.parentElement;
        var img_demo = loadImage.querySelectorAll('.img-demo');		
		var canvas = loadImage.querySelector('canvas');		
	    var ctx = canvas.getContext("2d");	

		canvas.style.width = "180px";		
		
		reader.onload = function(event) {
			_this.classList.add("img-uploaded");
			var img = new Image;
			img.onload = function() {	
                if (img_demo.length > 0) {
					img_demo.forEach(element => element.classList.add('hide'));
				}				
				
				canvas.width = img.width;
				canvas.height = img.height;
				ctx.drawImage(img,0,0);						
			};
			img.src = event.target.result;			
		};
		reader.readAsDataURL(e.target.files[0])
	};
    /*************************displaying uploaded images canvas*************************/
	
	function prepareImgW(form) {
		let img_galls = document.querySelectorAll('.convert-webp');
        img_galls.forEach(function(elem, index){
          var canv = elem.querySelector('canvas');
          elem.querySelector('.convert-canv').value = canv.toDataURL('image/webp');
        });
	}	
	
	function postMedia(th_id) {
		  
		const th_form = document.getElementById('media-'+th_id)		  
		const update_status = th_form.querySelector('.update-status');		
		const fileInput_img = document.querySelector('.media-img') ;
		const media_id = th_form.querySelector('.media_id').value;
		const media_opd = th_form.querySelector('.media-opd');
		  
		update_status.innerHTML = "<div style='color: orange'>Saving...</div>";
		  
		let data = new FormData(th_form)

		data.append('file', fileInput_img.files[0]);
		
		fetch('edit.php', {
			method: 'POST',
			body: data,
		})
		.then(response => response.json())   
		.then(json => {
			if (json.status === "ok") {				
				if(media_id=='null') {
					update_status.innerHTML = "<div style='color: green'>Post added successfully</div>";
					th_form.querySelector('.media_id').value = json.id
					media_opd.value = 'Save changes'
				} else {
					update_status.innerHTML = "<div style='color: green'>Changes saved</div>";
				}
			} else {
				update_status.innerHTML = "<div style='color: red'>Error: " + text + "</div>";
			}
		})
		.catch(err => { 
			update_status.innerHTML = "<div style='color: red'>Error JS: " + err + "</div>"; 
		});
	}	
	
	$(document).on('click', '.top-publ', function(){
		let _this = $(this);
		$('[name="top_media"]').not(_this).prop({'checked': false})
	})

</script>	
	
</body>
</html>
