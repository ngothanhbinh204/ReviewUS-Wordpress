module.exports = {
	content: ['./**/*.php', './**/*.js', './theme/**/*.php'],
	safelist: [
		'text-primary2',
		'bg-primary2',
		'border-primary2',
		'text-secondary2',
		'bg-secondary2',
		'border-secondary2',
		'text-danger-cus',
		'bg-danger-cus',
		'text-nt-redlink',
		'bg-nt-redlink',
		'border-nt-redlink',
	],
	theme: {
		extend: {
			spacing: {
				999: '999px', // Class thử nghiệm: p-999
			},
			maxWidth: {
				1440: '1440px',
				'screen-2xl': '1440px', // Ghi đè lại giá trị mặc định
			},
			screens: {
				'3xl': '1600px', // hoặc bạn có thể chọn giá trị khác như 1700px, 1800px tùy ý
			},
			colors: {
				primary2: 'rgb(179 23 6)', // màu custom
				secondary2: '#9a031e',
				'danger-cus': '#ff4d4f',
				'nt-redlink': 'rgb(179 23 6)',
			},
		},
	},
};
