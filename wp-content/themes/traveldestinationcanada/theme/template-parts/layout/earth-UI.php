  <div id="globe-container">
      <div class="loading" id="loading">Đang tải quả địa cầu...</div>
      <div id="default_info"
          class="group_info flex flex-col w-100 visible relative top-[10%] z-20 p-[20px] lg:absolute lg:right-[20px] lg:w-[450px] lg:bg-white lg:pt-[20px] 2xl:w-[480px]">
          <span class="text-base text-sm font-normal top_content">
              Canada, naturally.
          </span>
          <h4 class="leading-tight text-primary font-bold text-2xl md:text-[52px]">Once-in-a-lifetime happens all
              the time.
          </h4>
          <p class="text-base text-lg content">
              Whether it’s postcard-worthy views at every pitstop or warm-hearted folks always eager to offer a
              helping hand, the exceptional is just a part of everyday life in Canada. Wandering through cities can
              sometimes mean pausing your commute for a four-legged passerby. Sitting down to dinner often comes with
              an epic backdrop. And if the northern lights start stirring overhead? In the North, that's just a
              regular Tuesday. Extraordinary experiences come naturally here.


          </p>

          <a href="" class="button_primary"> Learn More</a>

      </div>


      <!-- <div class="controls">
            <button class="control-btn" onclick="resetView()">🏠 Reset View</button>
            <button class="control-btn" onclick="toggleRotation()">⏸️ Pause/Play</button>
        </div> -->

      <div id="globe"></div>

      <div class="info-popup" id="info-popup">
          <button class="close-btn" onclick="closePopup()">&times;</button>
          <div class="box_img relative h-[400px] ">
              <img class="h-full w-full object-cover"
                  src="http://destinationcanada.local/wp-content/uploads/2025/06/DC2018_Clara_Amfo-03975-min-1.webp"
                  alt="">
          </div>
          <h3 class="break-words text-[26px] font-bold leading-tight lg:text-[32px] 2xl:text-[36px] text-primary mb-4 !font-medium"
              id="popup-title"></h3>
          <p class="text-base" id="popup-description"></p>
          <div class="info-stats flex flex-col gap-1 md:gap-3">
              <a class="button_primary" href="">Discover Northwest Territories</a>
              <a class="button_redirect" id="popup-population" href="">Visit Spectacular Northwest Territories</a>
              <a class="button_redirect" id="popup-area" href="">Visit Spectacular Northwest Territories</a>

          </div>
      </div>
  </div>

  <script src="https://unpkg.com/globe.gl@2.24.3/dist/globe.gl.min.js"></script>
  <script>
let globe;
let isRotating = true;
let countries = [];
const VIETNAM_LAT = 14.0583;
const VIETNAM_LON = 108.2772;

// Dữ liệu mẫu cho các quốc gia
const countryData = {
    'Vietnam': {
        name: 'Việt Nam',
        description: 'Quốc gia Đông Nam Á với văn hóa phong phú và lịch sử lâu đời.',
        population: '97.3 triệu',
        area: '331,212 km²'
    },
    'China': {
        name: 'Trung Quốc',
        description: 'Quốc gia đông dân nhất thế giới với nền văn minh 5000 năm tuổi.',
        population: '1.4 tỷ',
        area: '9.6 triệu km²'
    },
    'United States': {
        name: 'Hoa Kỳ',
        description: 'Siêu cường thế giới với nền kinh tế và công nghệ phát triển.',
        population: '331 triệu',
        area: '9.8 triệu km²'
    },
    'Japan': {
        name: 'Nhật Bản',
        description: 'Đất nước mặt trời mọc với công nghệ hiện đại và văn hóa truyền thống.',
        population: '125 triệu',
        area: '377,975 km²'
    },
    'Germany': {
        name: 'Đức',
        description: 'Trung tâm kinh tế và công nghiệp của châu Âu.',
        population: '83 triệu',
        area: '357,022 km²'
    },
    'Brazil': {
        name: 'Brazil',
        description: 'Quốc gia lớn nhất Nam Mỹ với rừng Amazon rộng lớn.',
        population: '215 triệu',
        area: '8.5 triệu km²'
    },
    'India': {
        name: 'Ấn Độ',
        description: 'Nền văn minh cổ đại với sự đa dạng văn hóa và tôn giáo.',
        population: '1.38 tỷ',
        area: '3.3 triệu km²'
    },
    'Australia': {
        name: 'Úc',
        description: 'Lục địa riêng biệt với động vật hoang dã độc đáo.',
        population: '25.7 triệu',
        area: '7.7 triệu km²'
    }
};

async function initGlobe() {
    try {
        // Tải dữ liệu địa lý
        const response = await fetch(
            'https://raw.githubusercontent.com/holtzy/D3-graph-gallery/master/DATA/world.geojson');
        const worldData = await response.json();

        countries = worldData.features;

        // Khởi tạo globe
        globe = Globe()
            (document.getElementById('globe'))
            .globeImageUrl('//unpkg.com/three-globe/example/img/earth-night.jpg')
            .backgroundImageUrl('//unpkg.com/three-globe/example/img/night-sky.png')
            .lineHoverPrecision(0)
            .polygonsData(countries)
            .polygonAltitude(0.01)
            .polygonCapColor(feat => feat === hoverD ? 'rgba(255, 255, 255, 0.8)' : 'rgba(120, 120, 120, 0.6)')
            .polygonSideColor(() => 'rgba(0, 0, 0, 0.2)')
            .polygonStrokeColor(() => '#111')
            .polygonLabel(({
                properties: d
            }) => `
                        <div style="background: rgba(0,0,0,0.8); padding: 8px; border-radius: 5px; color: white;">
                            <b>${d.NAME || d.name || 'Unknown'}</b>
                        </div>
                    `)
            .onPolygonHover(hoverD => {
                globe.polygonCapColor(feat => feat === hoverD ? 'rgba(255, 255, 255, 0.8)' :
                    'rgba(120, 120, 120, 0.6)');
                document.body.style.cursor = hoverD ? 'pointer' : 'grab';
            })
            .onPolygonClick((polygon, event) => {
                if (polygon) {
                    const countryName = polygon.properties.NAME || polygon.properties.name;
                    showCountryInfo(countryName, event);
                    focusOnCountry(polygon);
                }
            })
            .width(document.getElementById('globe-container').offsetWidth)
            .height(1200);

        // Tự động xoay
        globe.controls().autoRotate = false;
        globe.controls().autoRotateSpeed = 0.5;

        // Vô hiệu hóa zoom bằng scroll wheel
        globe.controls().enableZoom = true; // Vẫn cho phép zoom bằng cách khác
        globe.controls().enablePan = false; // Tắt pan

        // Vô hiệu hóa scroll wheel zoom
        const globeElement = document.getElementById('globe');
        globeElement.addEventListener('wheel', function(e) {
            e.preventDefault();
            e.stopPropagation();
        }, {
            passive: false
        });

        // Chỉ cho phép drag để rotate
        globe.controls().mouseButtons = {
            LEFT: THREE.MOUSE.ROTATE,
            MIDDLE: null, // Tắt middle mouse
            RIGHT: null // Tắt right mouse
        };

        // Giới hạn khoảng cách zoom
        globe.controls().minDistance = 150;
        globe.controls().maxDistance = 300;

        // Ẩn loading
        document.getElementById('loading').style.display = 'none';

        // focusOnLocation(VIETNAM_LAT, VIETNAM_LON);
        focusOnLocation(11.6, 108.9); // Ninh Thuận


    } catch (error) {
        console.error('Lỗi khi tải dữ liệu:', error);
        document.getElementById('loading').innerHTML = 'Lỗi khi tải dữ liệu';
    }
}

let hoverD;

function showCountryInfo(countryName, event) {
    const popup = document.getElementById('info-popup');
    const data = countryData[countryName] || {
        name: countryName,
        description: 'Thông tin chi tiết sẽ được cập nhật sớm.',
        population: 'Đang cập nhật',
        area: 'Đang cập nhật'
    };

    document.getElementById('popup-title').textContent = data.name;
    document.getElementById('popup-description').textContent = data.description;
    document.getElementById('popup-population').textContent = data.population;
    document.getElementById('popup-area').textContent = data.area;

    // Vị trí popup
    const container = document.getElementById('globe-container');
    const rect = container.getBoundingClientRect();
    const maxX = container.offsetWidth - 370;
    const maxY = 600 - 250;

    const x = Math.min(Math.max(0, event.clientX - rect.left), maxX);
    const y = Math.min(Math.max(0, event.clientY - rect.top), maxY);

    popup.style.left = '0px';
    popup.style.top = '0px';
    popup.classList.add('show');
}

function closePopup() {
    document.getElementById('info-popup').classList.remove('show');
}

function focusOnCountry(polygon) {
    if (!polygon.geometry || !polygon.geometry.coordinates) return;

    // Tính toán tọa độ trung tâm
    let lat = 0,
        lng = 0,
        count = 0;

    function processCoords(coords) {
        if (Array.isArray(coords[0])) {
            coords.forEach(processCoords);
        } else {
            lng += coords[0];
            lat += coords[1];
            count++;
        }
    }

    if (polygon.geometry.type === 'Polygon') {
        processCoords(polygon.geometry.coordinates[0]);
    } else if (polygon.geometry.type === 'MultiPolygon') {
        polygon.geometry.coordinates.forEach(poly => processCoords(poly[0]));
    }

    if (count > 0) {
        lat /= count;
        lng /= count;

        // Di chuyển camera đến vị trí
        globe.pointOfView({
            lat,
            lng,
            altitude: 0.2
        }, 1500);
    }
}

function resetView() {
    globe.pointOfView({
        lat: 0,
        lng: 0,
        altitude: 1
    }, 1000);
    closePopup();
}

function toggleRotation() {
    isRotating = !isRotating;
    globe.controls().autoRotate = isRotating;

    const btn = document.querySelector('.controls .control-btn:nth-child(2)');
    btn.innerHTML = isRotating ? '⏸️ Pause/Play' : '▶️ Pause/Play';
}

function focusOnLocation(lat, lon, distance = 120) {
    const target = latLonToVector3(lat, lon, 100);

    gsap.to(camera.position, {
        duration: 1.5,
        x: target.x * (distance / 100),
        y: target.y * (distance / 100),
        z: target.z * (distance / 100),
        onUpdate: () => {
            camera.lookAt(0, 0, 0);
        }
    });
}

function latLonToVector3(lat, lon, radius = 100) {
    const phi = (90 - lat) * (Math.PI / 180);
    const theta = (lon + 180) * (Math.PI / 180);

    const x = -radius * Math.sin(phi) * Math.cos(theta);
    const z = radius * Math.sin(phi) * Math.sin(theta);
    const y = radius * Math.cos(phi);

    return new THREE.Vector3(x, y, z);
}



// Xử lý resize
window.addEventListener('resize', () => {
    if (globe) {
        const container = document.getElementById('globe-container');
        globe.width(container.offsetWidth).height(1200);
    }
});

// Đóng popup khi click outside
document.addEventListener('click', (e) => {
    const popup = document.getElementById('info-popup');
    if (!popup.contains(e.target) && !e.target.closest('#globe')) {
        closePopup();
    }
});

// Ngăn scroll wheel zoom trên toàn bộ container
document.getElementById('globe-container').addEventListener('wheel', function(e) {
    // Chỉ ngăn wheel event trên globe, không ảnh hưởng scroll trang
    if (e.target.closest('#globe')) {
        e.preventDefault();
        e.stopPropagation();
    }
}, {
    passive: false
});

// Khởi tạo
initGlobe();
  </script>
