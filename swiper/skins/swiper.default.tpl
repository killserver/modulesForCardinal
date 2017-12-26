<div class="swiperz-container">
	<div class="swiperz-scrollbar"></div>
	<div class="swiperz-button-prev"></div>
	<div class="swiperz-button-next"></div>
	<div class="swiperz-wrapper">
		[foreach block=sliderz]<div class="swiperz-slide" style="background-image: url('{C_default_http_local}{slide_img}');">
			<b>{slide_title}</b>
			<p>{slide_descr}</p>
		</div>[/foreach]
	</div>
	<div class="swiperz-pagination"></div>
</div>