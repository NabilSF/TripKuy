 // --- DATA LAYER ---
        const hotels = [
            { id: 1, name: 'Hotel Indonesia Kempinski', email: 'info.jakarta@kempinski.com', address: 'Jl. MH Thamrin No. 1, Jakarta Pusat', phone: '(021) 23583800', description: 'Hotel bintang 5 bersejarah dengan fasilitas mewah.', rating: 4.8, city: 'Jakarta' },
            { id: 2, name: 'The Gaia Hotel Bandung', email: 'reservation@thegaiabandung.com', address: 'Jl. Dr. Setiabudi No. 430, Bandung', phone: '(022) 20280780', description: 'Resor kontemporer dengan konsep Active/Rest.', rating: 4.7, city: 'Bandung' },
            { id: 3, name: 'Hotel Tentrem Yogyakarta', email: 'info.jogja@hoteltentrem.com', address: 'Jl. AM. Sangaji No. 72-74, Yogyakarta', phone: '(0274) 6415555', description: 'Kemewahan dengan keramahan tradisional Jawa.', rating: 4.6, city: 'Yogyakarta' }
        ];

        const roomTypes = {
            standard: { name: 'Standard Room', size: '25 m²', capacity: 2, description: 'Kamar standar nyaman untuk budget traveler.', features: ['Queen Size Bed', 'Free Wi-Fi', 'AC', 'Shower'] },
            deluxe: { name: 'Deluxe Room', size: '30 m²', capacity: 2, description: 'Pemandangan kota dengan fasilitas lengkap.', features: ['King Size Bed', 'Minibar', 'Smart TV', 'Bathub'] },
            executive: { name: 'Executive Suite', size: '45 m²', capacity: 2, description: 'Suite mewah dengan akses lounge eksklusif.', features: ['Lounge Access', 'Work Desk', 'Breakfast Included'] },
            presidential: { name: 'Presidential Suite', size: '120 m²', capacity: 4, description: 'Pelayanan butler 24 jam dan ruang tamu luas.', features: ['Private Butler', 'Jacuzzi', 'Kitchenette'] }
        };

        // --- APP LOGIC ---
        document.addEventListener('DOMContentLoaded', () => {
            initMenu();
            renderPopularHotels();
            renderHotelList();
            initRoomSelection();
            updateTime();
            setInterval(updateTime, 60000);
        });

        function initMenu() {
            document.querySelectorAll('.menu-item').forEach(btn => {
                btn.addEventListener('click', () => {
                    const pageId = btn.getAttribute('data-page');
                    
                    // Toggle Active Sidebar
                    document.querySelectorAll('.menu-item').forEach(b => b.classList.remove('active', 'bg-blue-600', 'text-white'));
                    btn.classList.add('active', 'bg-blue-600', 'text-white');

                    // Switch Page
                    document.querySelectorAll('.page-content').forEach(p => p.classList.remove('active'));
                    document.getElementById(pageId).classList.add('active');

                    // Update Header
                    const titles = { 'dashboard': 'Dashboard Admin', 'data-hotel': 'Data Hotel', 'management-kamar': 'Management Kamar' };
                    document.getElementById('pageHeader').innerText = titles[pageId] || pageId.replace('-', ' ');
                    
                    if(window.innerWidth < 1024) toggleSidebar();
                });
            });

            document.getElementById('menuToggle').addEventListener('click', toggleSidebar);
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        }

        function renderPopularHotels() {
            const container = document.getElementById('popularHotels');
            container.innerHTML = hotels.map(h => `
                <div class="bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition cursor-pointer">
                    <div class="h-40 bg-gray-200 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400')"></div>
                    <div class="p-4">
                        <h4 class="font-bold text-gray-900">${h.name}</h4>
                        <div class="flex items-center text-xs text-gray-500 mt-1 gap-3">
                            <span><i class="fas fa-map-marker-alt text-blue-500"></i> ${h.city}</span>
                            <span class="text-orange-500 font-bold"><i class="fas fa-star"></i> ${h.rating}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderHotelList() {
            const container = document.getElementById('hotelList');
            container.innerHTML = hotels.map(h => `
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] bg-gray-100 px-2 py-1 rounded text-gray-500 font-bold">ID: ${h.id}</span>
                            <h4 class="text-lg font-bold mt-1">${h.name}</h4>
                        </div>
                        <div class="flex gap-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded"><i class="fas fa-edit"></i></button>
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2"><i class="fas fa-phone text-gray-400"></i> ${h.phone}</div>
                        <div class="flex items-center gap-2"><i class="fas fa-envelope text-gray-400"></i> ${h.email}</div>
                    </div>
                </div>
            `).join('');
        }

        function initRoomSelection() {
            const btns = document.querySelectorAll('.room-type-item');
            btns.forEach(btn => {
                btn.addEventListener('click', () => {
                    btns.forEach(b => b.classList.remove('active', 'bg-white', 'shadow-sm', 'font-bold'));
                    btn.classList.add('active', 'bg-white', 'shadow-sm', 'font-bold');
                    renderRoomDetail(btn.getAttribute('data-room'));
                });
            });
            renderRoomDetail('standard');
        }

        function renderRoomDetail(type) {
            const room = roomTypes[type];
            document.getElementById('roomDetails').innerHTML = `
                <div class="animate-fadeIn">
                    <h3 class="text-2xl font-bold mb-2">${room.name}</h3>
                    <div class="flex gap-4 mb-6 text-sm">
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full"><i class="fas fa-expand mr-1"></i> ${room.size}</span>
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full"><i class="fas fa-user-friends mr-1"></i> ${room.capacity} Orang</span>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">${room.description}</p>
                    <h4 class="font-bold mb-4">Fasilitas Utama:</h4>
                    <div class="grid grid-cols-2 gap-3 mb-8">
                        ${room.features.map(f => `<div class="flex items-center gap-2 text-sm text-gray-600"><i class="fas fa-check-circle text-green-500"></i> ${f}</div>`).join('')}
                    </div>
                    <div class="flex gap-3">
                        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Edit Tipe Kamar</button>
                        <button class="border border-gray-200 px-6 py-2 rounded-lg hover:bg-gray-50 transition">Lihat Statistik</button>
                    </div>
                </div>
            `;
        }

        function updateTime() {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = new Date().toLocaleDateString('id-ID', options);
            document.getElementById('pageSubtitle').innerText = `Admin TripKuy • ${dateStr}`;
        }

        function openSettings() { alert("Buka pengaturan sistem..."); }
        function addHotel() { alert("Buka modal tambah hotel..."); }
        function showHotelList() { document.querySelector('[data-page="data-hotel"]').click(); }