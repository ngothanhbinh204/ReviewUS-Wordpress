// Main navigation functionality
document.addEventListener('DOMContentLoaded', function () {
	// Elements
	const placesToGoBtn = document.getElementById('placesToGoBtn');
	const dropdownMenu = document.getElementById('dropdownMenu');
	const tabButtons = document.querySelectorAll('.tab-btn');
	const tabContents = document.querySelectorAll('.tab-content');
	const mobileMenuBtn = document.getElementById('mobileMenuBtn');
	const mobileMenu = document.getElementById('mobileMenu');
	const closeMobileMenu = document.getElementById('closeMobileMenu');

	let isDropdownOpen = false;

	// Toggle dropdown menu
	function toggleDropdown() {
		isDropdownOpen = !isDropdownOpen;

		if (isDropdownOpen) {
			dropdownMenu.classList.remove('hidden');
			dropdownMenu.classList.add('animate-fadeIn');
			placesToGoBtn.querySelector('svg').style.transform =
				'rotate(180deg)';
		} else {
			dropdownMenu.classList.add('hidden');
			dropdownMenu.classList.remove('animate-fadeIn');
			placesToGoBtn.querySelector('svg').style.transform = 'rotate(0deg)';
		}
	}

	// Handle tab switching
	function switchTab(targetTab) {
		// Update tab buttons
		tabButtons.forEach((btn) => {
			btn.classList.remove('active');
			btn.classList.add('text-gray-600', 'hover:text-gray-800');
			btn.classList.remove('bg-white', 'text-primary', 'shadow-sm');
		});

		// Hide all tab contents first
		tabContents.forEach((content) => {
			content.classList.remove('active');
			content.classList.add('hidden');
		});

		// Activate selected tab button
		const activeBtn = document.querySelector(`[data-tab="${targetTab}"]`);
		if (activeBtn) {
			activeBtn.classList.add('active');
			activeBtn.classList.remove('text-gray-600', 'hover:text-gray-800');
			activeBtn.classList.add('bg-white', 'text-primary', 'shadow-sm');
		}

		// Show selected tab content
		const activeContent = document.querySelector(
			`[data-content="${targetTab}"]`
		);
		if (activeContent) {
			activeContent.classList.remove('hidden');
			activeContent.classList.add('active');
		}
	}

	// Add CSS for animations
	const style = document.createElement('style');
	style.textContent = `
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fadeIn {
      animation: fadeIn 0.3s ease-out;
    }

    .tab-content {
      transition: all 0.2s ease-in-out;
    }

    .tab-content.hidden {
      display: none !important;
    }

    .tab-content.active {
      display: block;
      opacity: 1;
    }

    .tab-btn {
      position: relative;
      overflow: hidden;
      transition: all 0.2s ease-in-out;
    }

    .tab-btn:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .tab-btn:hover:before {
      left: 100%;
    }

    .tab-btn.active {
      background-color: white !important;
      color: #e53e3e !important;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .tab-btn:not(.active) {
      color: #6b7280;
      background-color: transparent;
    }

    .tab-btn:not(.active):hover {
      color: #374151;
      background-color: rgba(255, 255, 255, 0.5);
    }

    .destination-card {
      transform-origin: center;
      transition: transform 0.3s ease-in-out;
    }

    .destination-card:hover {
      transform: translateY(-4px);
    }
  `;
	document.head.appendChild(style);

	// Event listeners
	placesToGoBtn.addEventListener('click', toggleDropdown);

	// Close dropdown when clicking outside
	document.addEventListener('click', function (event) {
		if (
			!dropdownMenu.contains(event.target) &&
			!placesToGoBtn.contains(event.target)
		) {
			if (isDropdownOpen) {
				toggleDropdown();
			}
		}
	});

	// Tab button event listeners
	tabButtons.forEach((btn) => {
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			const targetTab = this.getAttribute('data-tab');
			switchTab(targetTab);
		});
	});

	// Mobile menu handlers
	mobileMenuBtn.addEventListener('click', function () {
		mobileMenu.classList.remove('hidden');
	});

	closeMobileMenu.addEventListener('click', function () {
		mobileMenu.classList.add('hidden');
	});

	// Close mobile menu when clicking overlay
	mobileMenu.addEventListener('click', function (event) {
		if (event.target === mobileMenu) {
			mobileMenu.classList.add('hidden');
		}
	});

	// Handle escape key
	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			if (isDropdownOpen) {
				toggleDropdown();
			}
			if (!mobileMenu.classList.contains('hidden')) {
				mobileMenu.classList.add('hidden');
			}
		}
	});

	// Add destination card click handlers
	document.querySelectorAll('.destination-card').forEach((card) => {
		card.addEventListener('click', function () {
			const destination = this.querySelector('h3').textContent;
			console.log(`Clicked on ${destination}`);
			// Add your navigation logic here
		});
	});

	// Handle responsive behavior
	function handleResize() {
		if (window.innerWidth >= 768) {
			mobileMenu.classList.add('hidden');
		}
	}

	window.addEventListener('resize', handleResize);

	// Initialize first tab as active
	switchTab('western');
});

// Register GSAP Paralaxx Img
document.addEventListener('DOMContentLoaded', () => {
	// Khởi tạo GSAP
	gsap.registerPlugin(ScrollTrigger);

	// Lấy tất cả phần tử
	const rightElements = gsap.utils.toArray('.img_parallax_toRight');
	const leftElements = gsap.utils.toArray('.img_parallax_toLeft');

	// Xử lý từng cặp right-left theo thứ tự
	rightElements.forEach((rightEl, index) => {
		const rightAnimation = gsap.to(rightEl.querySelector('img'), {
			x: 50,
			ease: 'none',
			scrollTrigger: {
				trigger: rightEl,
				start: 'top bottom', // Bắt đầu khi phần tử chạm đáy viewport
				end: '60% top', // Kết thúc khi phần tử rời khỏi đỉnh viewport
				scrub: true,
				markers: false, // Bật markers nếu cần debug
				id: `right-${index}`, // Gán ID để debug
			},
		});

		if (leftElements[index]) {
			gsap.to(leftElements[index].querySelector('img'), {
				x: -50,
				ease: 'none',
				scrollTrigger: {
					trigger: leftElements[index],
					start: () => {
						// Lấy vị trí kết thúc của animation toRight
						return rightAnimation.scrollTrigger
							? rightAnimation.scrollTrigger.end
							: 'top bottom'; // Fallback nếu không tồn tại
					},
					end: 'bottom top',
					scrub: true,
					markers: false,
					id: `left-${index}`,
				},
			});
		}
	});
});

// Swiper Slider
document.addEventListener('DOMContentLoaded', function () {
	if (typeof Swiper !== 'undefined') {
		const swiper = new Swiper('.travelSwiper', {
			slidesPerView: 3,
			centeredSlides: true,
			spaceBetween: 30,
			loop: true,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
			breakpoints: {
				0: { slidesPerView: 1 },
				768: { slidesPerView: 2 },
				1024: { slidesPerView: 3 },
			},
		});
	} else {
		console.error('Swiper is not defined');
	}
});

// Swiper for Place To Go

document.addEventListener('DOMContentLoaded', function () {
	if (typeof Swiper !== 'undefined') {
		const swiperBannerPlaceToGo = new Swiper('.bannerPlaceToGo', {
			slidesPerView: 1,
			centeredSlides: true,
			loop: false,
			watchOverflow: true,
			navigation: {
				nextEl: '.swiper-placetogobanner-button-next',
				prevEl: '.swiper-placetogobanner-button-prev',
			},
			on: {
				init: function () {
					toggleNavButtons(this);
				},
				slideChange: function () {
					toggleNavButtons(this);
				},
			},
		});
	} else {
		console.error('Swiper is not defined');
	}
});

function toggleNavButtons(swiper) {
	const prevBtn = swiper.navigation.prevEl;
	const nextBtn = swiper.navigation.nextEl;

	if (swiper.isBeginning) {
		prevBtn.classList.add('swiper_cus_hidden');
	} else {
		prevBtn.classList.remove('swiper_cus_hidden');
	}

	if (swiper.isEnd) {
		nextBtn.classList.add('swiper_cus_hidden');
	} else {
		nextBtn.classList.remove('swiper_cus_hidden');
	}
}

document.querySelectorAll('.video-slide').forEach((video) => {
	video.addEventListener('click', () => {
		if (video.requestFullscreen) {
			video.requestFullscreen();
		} else if (video.webkitRequestFullscreen) {
			video.webkitRequestFullscreen(); // Safari
		} else if (video.msRequestFullscreen) {
			video.msRequestFullscreen(); // IE/Edge
		}
	});
});

// Add SVG tag a in wrapper_content_single
jQuery(document).ready(function ($) {
	// Chỉ xử lý các thẻ <a> bên trong phần nội dung chi tiết
	$('.wrapper_content_single a').each(function () {
		const svg = `
		<svg width="32" height="32" fill="inherit" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="relative bottom-[-1px] ml-1 mr-1 inline h-3 w-3 fill-current"><title id="ExternalLinkIconId">External Link Title</title><g clip-path="url(#clip0_3048_45488)"><path d="M13.6837 11.4373H15.992V16.0004H0V0.00305176H4.53895V2.3193C3.97994 2.3193 3.4343 2.338 2.88867 2.31395H2.29756C2.31093 5.7389 2.31093 9.73289 2.29756 13.1578C2.29756 13.5719 2.29756 13.6921 2.29756 13.6921C5.72651 13.6815 10.2628 13.6815 13.6917 13.6921L13.6864 13.1525C13.6623 12.6022 13.681 12.0518 13.681 11.4373H13.6837Z"></path><path d="M15.9999 7.99599H13.7051V4.25046C11.782 6.17399 9.92305 8.03339 8.06413 9.88746L6.42188 8.10286C8.25404 6.2755 10.2199 4.31191 12.1832 2.35365H8.01599V0H15.9999V7.99599Z"></path></g><defs><clipPath id="clip0_3048_45488"><rect width="32" height="32"></rect></clipPath></defs></svg>
		`;
		if (!$(this).hasClass('no-icon') && $(this).find('svg').length === 0) {
			$(this).append(svg);
		}
	});
});

document.addEventListener('DOMContentLoaded', function () {
	if (typeof Swiper !== 'undefined') {
		const swiper1 = new Swiper('.SliderNatural', {
			slidesPerView: 1,
			centeredSlides: true,
			centeredSlidesBounds: true,
			spaceBetween: 24,
			loop: false,
			navigation: {
				nextEl: '.swiper--natural-button-next',
				prevEl: '.swiper--natural-button-prev',
			},

			breakpoints: {
				768: {
					slidesPerView: 2,
					spaceBetween: 20,
					simulateTouch: true,
					grabCursor: true,
				},
				1024: {
					slidesPerView: 4,
					allowTouchMove: false,
					simulateTouch: false,
					grabCursor: false,
				},
			},
		});
	} else {
		console.error('Swiper is not defined');
	}
});

// document.addEventListener('DOMContentLoaded', function () {
// 	if (typeof Swiper !== 'undefined') {
// 		const swiper1 = new Swiper('.sliderGeneral3_5', {
// 			slidesPerView: 2.2,
// 			spaceBetween: 24,
// 			speed: 600,
// 			freeMode: {
// 				enabled: false, // Nếu muốn snap slide
// 			},
// 			grabCursor: true,
// 			loop: false,
// 			navigation: {
// 				nextEl: '.swiper--general-button-next',
// 				prevEl: '.swiper--general-button-prev',
// 			},

// 			breakpoints: {
// 				480: {
// 					slidesPerView: 1.2,
// 					slidesPerGroup: 1,
// 				},
// 				768: {
// 					slidesPerView: 2.2,
// 					slidesPerGroup: 2,
// 				},
// 				1024: {
// 					slidesPerView: 3.2,
// 					slidesPerGroup: 3,
// 				},
// 			},
// 		});
// 	} else {
// 		console.error('Swiper is not defined');
// 	}
// });

// Slider 4,2

// document.addEventListener('DOMContentLoaded', function () {
// 	if (typeof Swiper !== 'undefined') {
// 		const swiper1 = new Swiper('.slider4_2', {
// 			slidesPerView: 1.2,
// 			spaceBetween: 24,
// 			speed: 600,
// 			freeMode: {
// 				enabled: false, // Nếu muốn snap slide
// 			},
// 			grabCursor: true,
// 			loop: false,
// 			navigation: {
// 				nextEl: '.swiper--general-button-next',
// 				prevEl: '.swiper--general-button-prev',
// 			},

// 			breakpoints: {
// 				480: {
// 					slidesPerView: 1,
// 					slidesPerGroup: 1,
// 				},
// 				768: {
// 					slidesPerView: 2.2,
// 					slidesPerGroup: 2,
// 				},
// 				1024: {
// 					slidesPerView: 3.2,
// 					slidesPerGroup: 3,
// 				},
// 				1280: {
// 					slidesPerView: 4.2,
// 					slidesPerGroup: 4,
// 				},
// 			},
// 		});
// 	} else {
// 		console.error('Swiper is not defined');
// 	}
// });

// Tạo HTML button dựa trên những input đã render trong Map

// Editor Map
document.addEventListener('DOMContentLoaded', function () {
	const wrappers = document.querySelectorAll('.map-wrapper');

	wrappers.forEach((wrapper) => {
		const filtersContainer = wrapper.querySelector('.map-filters');

		function renderButtons() {
			const inputs = wrapper.querySelectorAll('.mapster-cat-toggle');

			if (inputs.length === 0) return;
			if (filtersContainer.children.length > 0) return;

			inputs.forEach((input) => {
				const termId = input.getAttribute('data-term');

				// ❗❗ Tìm toàn bộ label trong DOM, không giới hạn wrapper, vì label do plugin render có thể nằm ngoài wrapper
				const label = document.querySelector(`label[for="${termId}"]`);

				if (!label) return;

				// Tắt tất cả lớp checked ban đầu
				if (input.checked) {
					input.checked = false;
					input.dispatchEvent(new Event('change', { bubbles: true }));
				}

				const btn = document.createElement('button');
				btn.className = 'filter-btn';
				btn.setAttribute('data-term', termId);
				btn.textContent = label.textContent.trim();

				filtersContainer.appendChild(btn);
			});

			// Bắt sự kiện click filter
			filtersContainer.addEventListener('click', function (e) {
				if (!e.target.classList.contains('filter-btn')) return;

				const btn = e.target;
				const termId = btn.dataset.term;

				const checkbox = wrapper.querySelector(
					`.mapster-cat-toggle[data-term="${termId}"]`
				);

				if (checkbox) {
					const isActive = btn.classList.contains('active');
					checkbox.checked = !isActive;
					checkbox.dispatchEvent(
						new Event('change', { bubbles: true })
					);
					btn.classList.toggle('active');
				}
			});
		}

		const observer = new MutationObserver(renderButtons);
		observer.observe(wrapper, { childList: true, subtree: true });
	});
});

// document.addEventListener('DOMContentLoaded', function () {
// 	const observer = new MutationObserver(() => {
// 		document.querySelectorAll('.maplibregl-popup-content').forEach((el) => {
// 			const text = el.textContent.trim().toLowerCase();

// 			if (text.includes('sân bay')) {
// 				el.classList.add('popup-airport');
// 			} else if (text.includes('bờ biển')) {
// 				el.classList.add('popup-beach');
// 			} else if (text.includes('tháp chăm')) {
// 				el.classList.add('popup-temple');
// 			} else if (text.includes('nông trại')) {
// 				el.classList.add('popup-farm');
// 			} else if (text.includes('trung tâm')) {
// 				el.classList.add('popup-city');
// 			}
// 		});
// 	});

// 	observer.observe(document.body, { childList: true, subtree: true });
// });

// Fade Up Slide

// Things To Do Filter
jQuery(document).ready(function ($) {
	let activeFilters = {
		territories: [],
		themes: [],
		seasons: [],
	};

	let appliedFilters = {
		territories: [],
		themes: [],
		seasons: [],
	};

	let currentPage = 1;
	let maxPages = 1;

	$('.apply-filter_btn_thingtodo').click(function () {
		const filterType = $(this).data('filter');
		const checkedValues = [];

		$('#' + filterType + '-dropdown input:checked').each(function () {
			checkedValues.push($(this).val());
		});

		activeFilters[filterType] = checkedValues.map((value) => ({
			value: value,
			label: $(
				'#' + filterType + '-dropdown input[value="' + value + '"]'
			)
				.next('span')
				.text(),
		}));

		appliedFilters[filterType] = [...checkedValues];

		currentPage = 1;
		updateFilterTags();
		loadFilteredThings();
		closeAllDropdowns();

		updateActiveFilterButtons();
	});

	$('.filter_btn_thingtodo').click(function (e) {
		e.preventDefault();
		e.stopPropagation();

		const $wrapper = $(this).closest('.filter-wrapper');
		const $dropdown = $wrapper.find('.filter-dropdown');
		const isVisible = $dropdown.is(':visible');

		// Ẩn tất cả dropdown khác
		$('.filter-dropdown').slideUp(150);
		$('.filter_btn_thingtodo').removeClass('active');

		// Toggle dropdown hiện tại
		if (!isVisible) {
			$dropdown.slideDown(200);
			$(this).addClass('active');
		}
	});

	$('.filter-dropdown').click(function (e) {
		e.stopPropagation();
	});

	$(document).click(function (e) {
		if (
			!$(e.target).closest('.filter_btn_thingtodo').length &&
			!$(e.target).closest('.filter-dropdown').length
		) {
			closeAllDropdowns();
		}
	});

	$('.reset-filter_btn_thingtodo').click(function () {
		const filterType = $(this).data('filter');
		$('#' + filterType + '-dropdown input').prop('checked', false);
		activeFilters[filterType] = [];
		currentPage = 1;
		updateFilterTags();
		loadFilteredThings();
		closeAllDropdowns();
		updateActiveFilterButtons();
	});

	$(document).on('click', '.remove-tag', function () {
		const filterType = $(this).data('filter');
		const value = $(this).data('value');

		activeFilters[filterType] = activeFilters[filterType].filter(
			(f) => f.value !== value
		);
		$('#' + filterType + '-dropdown input[value="' + value + '"]').prop(
			'checked',
			false
		);
		currentPage = 1;
		updateFilterTags();
		loadFilteredThings();
	});

	$('.filter-dropdown input[type="checkbox"]').on('change', function () {
		const $dropdown = $(this).closest('.filter-dropdown');
		const anyChecked =
			$dropdown.find('input[type="checkbox"]:checked').length > 0;
		$dropdown
			.find('.apply-filter_btn_thingtodo')
			.prop('disabled', !anyChecked);
		$dropdown
			.find('.reset-filter_btn_thingtodo')
			.prop('disabled', !anyChecked);
	});

	$('.clear-all-filters').click(function () {
		activeFilters = {
			territories: [],
			themes: [],
			seasons: [],
		};
		$('.filter-dropdown input').prop('checked', false);
		currentPage = 1;
		updateFilterTags();
		loadFilteredThings();

		updateActiveFilterButtons();
	});

	$('.loadMoreFilter').click(function () {
		currentPage++;
		loadFilteredThings(true);
	});

	function updateFilterTags() {
		const container = $('.filter-tags');
		container.empty();
		let any = false;

		Object.keys(activeFilters).forEach((type) => {
			activeFilters[type].forEach((filter) => {
				any = true;
				container.append(`
					<div class="filter-tag">
						<span>${filter.label}</span>
						<button class="remove-tag" data-filter="${type}" data-value="${filter.value}">×</button>
					</div>
				`);
			});
		});

		$('.clear-all-filters').toggle(any);
	}

	function loadFilteredThings(loadMore = false) {
		const $loadMoreBtn = $('.loadMoreFilter');
		const $loader = $loadMoreBtn.find('.loader_inButton');
		const $loadMoreText = $loadMoreBtn.find('.loadMoreText');

		$.ajax({
			url: things_ajax.ajax_url,
			type: 'POST',
			data: {
				action: 'filter_things_to_do',
				filters: activeFilters,
				nonce: things_ajax.nonce,
				page: currentPage,
			},
			beforeSend: function () {
				if (!loadMore) {
					$('#posts-container').html('<div class="loader"></div>');
				} else {
					$loadMoreBtn
						.addClass('button_disable')
						.prop('disabled', true);
					$loader.removeClass('hidden').addClass('inline-block');
					$loadMoreText.addClass('opacity-50');
				}
			},
			success: function (res) {
				if (res.success) {
					if (loadMore) {
						const tempDiv = document.createElement('div');
						tempDiv.innerHTML = res.data.html;
						const newCards =
							tempDiv.querySelectorAll('.card_thing');
						// 2. Append thủ công từng item mới vào container
						newCards.forEach((card) => {
							document
								.querySelector('#posts-container')
								.appendChild(card);
						});
						// 3. Animate chỉ các item mới
						animateNewCards(newCards);
					} else {
						$('#posts-container').html(res.data.html);
					}

					maxPages = res.data.max_num_pages;
					if (currentPage >= maxPages) {
						$loadMoreBtn.hide();
					} else {
						$loadMoreBtn.show();
					}
					$('#results-count').text(res.data.current_shown);
					$('#totalCount').text(res.data.count); // Tổng số từ server
				} else {
					$('#posts-container').html(
						'<div class="error">No results found.</div>'
					);
					$loadMoreBtn.hide();
				}
			},
			error: function () {
				$('#posts-container').html(
					'<div class="error">Error loading content.</div>'
				);
				$loadMoreBtn.hide();
			},
			complete: function () {
				// Reset trạng thái nút
				$loadMoreBtn
					.removeClass('button_disable')
					.prop('disabled', false);
				$loader.addClass('hidden').removeClass('inline-block');
				$loadMoreText.removeClass('opacity-50');
			},
		});
	}

	function closeAllDropdowns() {
		Object.keys(activeFilters).forEach((filterType) => {
			const selectedValues = activeFilters[filterType].map(
				(item) => item.value
			);
			$('#' + filterType + '-dropdown input[type="checkbox"]').each(
				function () {
					$(this).prop(
						'checked',
						selectedValues.includes($(this).val())
					);
				}
			);
			const anyChecked = selectedValues.length > 0;
			$('#' + filterType + '-dropdown')
				.find(
					'.apply-filter_btn_thingtodo, .reset-filter_btn_thingtodo'
				)
				.prop('disabled', !anyChecked);
		});
		$('.filter-dropdown').hide();
		$('.filter_btn_thingtodo').removeClass('active');
	}

	function updateActiveFilterButtons() {
		Object.keys(activeFilters).forEach((type) => {
			const hasChecked = activeFilters[type].length > 0;
			const $button = $(
				'.filter_btn_thingtodo[data-filter="' + type + '"]'
			);
			// const $badge = $button.find('.filter-badge');

			if (hasChecked) {
				$button.addClass('active_bg');
				// $badge.removeClass('hidden').text(activeFilters[type].length);
			} else {
				$button.removeClass('active_bg');
				// $badge.addClass('hidden').text('✔');
				// $badge.text(
				// 	activeFilters[type].length > 1
				// 		? activeFilters[type].length
				// 		: ''
				// );
			}
		});
	}

	// 1. Get query params at URL
	function getURLParams() {
		const params = {};
		window.location.search
			.replace('?', '')
			.split('&')
			.forEach((param) => {
				const [key, value] = param.split('=');
				if (key && value) {
					if (params[key]) {
						params[key] = [].concat(
							params[key],
							decodeURIComponent(value)
						);
					} else {
						params[key] = decodeURIComponent(value);
					}
				}
			});
		return params;
	}

	// 2. Áp dụng filter từ URL (nếu có)
	function applyURLFilters() {
		const urlParams = getURLParams();
		const filterKeys = ['territories', 'themes', 'seasons'];

		let hasAny = false;

		filterKeys.forEach((key) => {
			if (urlParams[key]) {
				const values = Array.isArray(urlParams[key])
					? urlParams[key]
					: [urlParams[key]];

				values.forEach((val) => {
					const selector = `#${key}-dropdown input[value="${val}"]`;
					$(selector).prop('checked', true);
				});

				activeFilters[key] = values.map((value) => ({
					value: value,
					label: $(`#${key}-dropdown input[value="${value}"]`)
						.next('span')
						.text(),
				}));

				appliedFilters[key] = [...values];

				// Hiển thị class active_bg cho nút
				$(`.filter_btn_thingtodo[data-filter="${key}"]`).addClass(
					'active_bg'
				);

				hasAny = true;
			}
		});

		if (hasAny) {
			updateFilterTags();
		}
	}

	// 3. Gọi sau khi định nghĩa function
	applyURLFilters();

	loadFilteredThings();
});

// Animate Card Setup
function animateCards() {
	gsap.from('.card_thing', {
		y: 50,
		opacity: 0,
		duration: 0.6,
		stagger: 0.1,
		ease: 'power2.out',
		scrollTrigger: {
			trigger: '.card_thing',
			start: 'top 95%',
			toggleActions: 'play none none none',
			once: true,
		},
	});
}
function animateNewCards(cards) {
	cards.forEach((card) => {
		if (!card.classList.contains('animated')) {
			gsap.from(card, {
				opacity: 0,
				y: 50,
				duration: 0.8,
				ease: 'power2.out',
			});
			card.classList.add('animated');
		}
	});
}

// sub nav scroll to section for click'

// document.addEventListener('DOMContentLoaded', function () {
// 	const header = document.getElementById('main-header');
// 	const subnav = document.querySelector('.subnav');
// 	const navLinks = document.querySelectorAll('.subnav-item');
// 	const marker = document.getElementById('subnav-marker');
// 	const labelSpan = document.getElementById('dropdownLabel');

// 	let lastScrollTop = 0;
// 	let isSubnavSticky = false;
// 	let hasSubnavBeenSticky = false;

// 	// === Sticky Detection ===
// 	if (marker && subnav) {
// 		const stickyObserver = new IntersectionObserver(
// 			([entry]) => {
// 				isSubnavSticky = !entry.isIntersecting;
// 				if (isSubnavSticky) {
// 					subnav.classList.add('is-sticky');
// 					hasSubnavBeenSticky = true;
// 				} else {
// 					subnav.classList.remove('is-sticky');
// 				}
// 			},
// 			{ threshold: 0 }
// 		);
// 		stickyObserver.observe(marker);
// 	}

// 	// === Header scroll behavior ===
// 	window.addEventListener('scroll', function () {
// 		const currentScroll =
// 			window.pageYOffset || document.documentElement.scrollTop;
// 		const scrollingDown = currentScroll > lastScrollTop;
// 		const scrollingUp = currentScroll < lastScrollTop;

// 		if (scrollingDown && currentScroll > 50) {
// 			header.classList.add('hidden_header');
// 			header.classList.remove('visible_header');
// 		} else if (scrollingUp) {
// 			if (!isSubnavSticky) {
// 				header.classList.remove('hidden_header');
// 				header.classList.add('visible_header');
// 			} else {
// 				header.classList.add('hidden_header');
// 				header.classList.remove('visible_header');
// 			}
// 		}
// 		lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
// 	});

// 	// === Highlight nav when scroll ===
// 	const sections = Array.from(navLinks)
// 		.map((link) => document.querySelector(link.getAttribute('href')))
// 		.filter(Boolean);

// 	const seenSections = new Set();

// 	const sectionObserver = new IntersectionObserver(
// 		(entries) => {
// 			if (!hasSubnavBeenSticky) return;

// 			let hasActive = false;
// 			entries.forEach((entry) => {
// 				if (entry.isIntersecting) {
// 					const id = entry.target.id;
// 					hasActive = true;

// 					// Set active
// 					navLinks.forEach((link) => link.classList.remove('active'));
// 					const activeLink = document.querySelector(
// 						`.subnav-item[href="#${id}"]`
// 					);
// 					if (activeLink) activeLink.classList.add('active');

// 					// Update label
// 					if (labelSpan)
// 						labelSpan.textContent = activeLink?.textContent.trim();
// 				}
// 			});

// 			if (!hasActive) {
// 				navLinks.forEach((link) => link.classList.remove('active'));
// 				if (window.scrollY < subnav.offsetTop - 100 && labelSpan) {
// 					labelSpan.textContent = 'Jump to section';
// 				}
// 			}
// 		},
// 		{
// 			threshold: 0.5,
// 			rootMargin: '-20% 0px -40% 0px',
// 		}
// 	);

// 	sections.forEach((section) => sectionObserver.observe(section));

// 	// === Smooth scroll on click ===
// 	navLinks.forEach((link) => {
// 		link.addEventListener('click', function (e) {
// 			e.preventDefault();
// 			const target = document.querySelector(this.getAttribute('href'));
// 			if (target) {
// 				const offset =
// 					subnav.offsetHeight +
// 					(document.body.classList.contains('admin-bar') ? 32 : 0) +
// 					60;
// 				const top =
// 					target.getBoundingClientRect().top +
// 					window.pageYOffset -
// 					offset;

// 				window.scrollTo({ top, behavior: 'smooth' });

// 				// cập nhật label dropdown
// 				if (labelSpan) labelSpan.textContent = this.textContent.trim();
// 			}
// 		});
// 	});

// 	// Show header nếu ở top
// 	if (window.pageYOffset === 0) {
// 		header.classList.remove('hidden_header');
// 		header.classList.add('visible_header');
// 	}
// });

// // === Dropdown toggle cho mobile ===
// const dropdownToggleSticky = document.getElementById('dropdownToggleSticky');
// const dropdownMenuSticky = document.getElementById('dropdownMenuSticky');

// if (dropdownToggleSticky && dropdownMenuSticky) {
// 	dropdownToggleSticky.addEventListener('click', () => {
// 		dropdownMenuSticky.classList.toggle('hidden');
// 	});
// }

// // Tự ẩn dropdown sau khi click
// const dropdownLinks = dropdownMenuSticky?.querySelectorAll('a') || [];
// dropdownLinks.forEach((link) => {
// 	link.addEventListener('click', () => {
// 		dropdownMenuSticky.classList.add('hidden');
// 	});
// });
// sub nav scroll to section for click

document.addEventListener('DOMContentLoaded', function () {
	const header = document.getElementById('main-header');
	const subnav = document.querySelector('.subnav');
	const navLinks = document.querySelectorAll('.subnav-item');
	const marker = document.getElementById('subnav-marker');
	const labelSpan = document.getElementById('dropdownLabel');

	let lastScrollTop = 0;
	let isSubnavSticky = false;
	let hasSubnavBeenSticky = false;

	// === Sticky Detection ===
	if (marker && subnav) {
		const stickyObserver = new IntersectionObserver(
			([entry]) => {
				isSubnavSticky = !entry.isIntersecting;
				if (isSubnavSticky) {
					subnav.classList.add('is-sticky');
					hasSubnavBeenSticky = true;
				} else {
					subnav.classList.remove('is-sticky');
				}
			},
			{ threshold: 0 }
		);
		stickyObserver.observe(marker);
	}

	// === Header scroll behavior ===
	window.addEventListener('scroll', function () {
		const currentScroll =
			window.pageYOffset || document.documentElement.scrollTop;
		const scrollingDown = currentScroll > lastScrollTop;
		const scrollingUp = currentScroll < lastScrollTop;

		if (scrollingDown && currentScroll > 50) {
			header.classList.add('hidden_header');
			header.classList.remove('visible_header');
		} else if (scrollingUp) {
			if (!isSubnavSticky) {
				header.classList.remove('hidden_header');
				header.classList.add('visible_header');
			} else {
				header.classList.add('hidden_header');
				header.classList.remove('visible_header');
			}
		}
		lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
	});

	// === Highlight nav when scroll ===
	const sections = Array.from(navLinks)
		.map((link) => document.querySelector(link.getAttribute('href')))
		.filter(Boolean);

	// Function to update active nav
	function updateActiveNav() {
		if (!hasSubnavBeenSticky) return;

		const scrollPos = window.scrollY;
		const offset = subnav.offsetHeight + 100; // Offset để tính toán chính xác hơn

		let currentSection = null;
		let currentSectionId = null;

		// Tìm section hiện tại dựa trên scroll position
		sections.forEach((section) => {
			const sectionTop = section.offsetTop - offset;
			const sectionBottom = sectionTop + section.offsetHeight;

			if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
				currentSection = section;
				currentSectionId = section.id;
			}
		});

		// Nếu không tìm thấy section nào, kiểm tra section cuối cùng
		if (!currentSection && sections.length > 0) {
			const lastSection = sections[sections.length - 1];
			const lastSectionTop = lastSection.offsetTop - offset;

			if (scrollPos >= lastSectionTop) {
				currentSection = lastSection;
				currentSectionId = lastSection.id;
			}
		}

		// Nếu vẫn không có section nào và scroll ở top
		if (
			!currentSection &&
			scrollPos < (sections[0]?.offsetTop - offset || 0)
		) {
			// Reset về trạng thái ban đầu
			navLinks.forEach((link) => link.classList.remove('active'));
			if (labelSpan) {
				labelSpan.textContent = 'Jump to section';
			}
			return;
		}

		// Update active state
		if (currentSectionId) {
			navLinks.forEach((link) => link.classList.remove('active'));
			const activeLink = document.querySelector(
				`.subnav-item[href="#${currentSectionId}"]`
			);

			if (activeLink) {
				activeLink.classList.add('active');
				if (labelSpan) {
					labelSpan.textContent = activeLink.textContent.trim();
				}
			}
		}
	}

	// Sử dụng scroll event thay vì IntersectionObserver
	window.addEventListener('scroll', updateActiveNav);

	// === Smooth scroll on click ===
	navLinks.forEach((link) => {
		link.addEventListener('click', function (e) {
			e.preventDefault();
			const target = document.querySelector(this.getAttribute('href'));
			if (target) {
				const offset =
					subnav.offsetHeight +
					(document.body.classList.contains('admin-bar') ? 32 : 0) +
					60;
				const top =
					target.getBoundingClientRect().top +
					window.pageYOffset -
					offset;

				window.scrollTo({ top, behavior: 'smooth' });

				// Update label dropdown
				if (labelSpan) labelSpan.textContent = this.textContent.trim();
			}
		});
	});

	// Show header nếu ở top
	if (window.pageYOffset === 0) {
		header.classList.remove('hidden_header');
		header.classList.add('visible_header');
	}
});

// === Dropdown toggle cho mobile ===
const dropdownToggleSticky = document.getElementById('dropdownToggleSticky');
const dropdownMenuSticky = document.getElementById('dropdownMenuSticky');

if (dropdownToggleSticky && dropdownMenuSticky) {
	dropdownToggleSticky.addEventListener('click', () => {
		dropdownMenuSticky.classList.toggle('hidden');
	});
}

// Tự ẩn dropdown sau khi click
const dropdownLinks = dropdownMenuSticky?.querySelectorAll('a') || [];
dropdownLinks.forEach((link) => {
	link.addEventListener('click', () => {
		dropdownMenuSticky.classList.add('hidden');
	});
});
