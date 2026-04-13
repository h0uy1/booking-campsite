<x-admin>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <div class="space-y-8 pb-12" x-data="slotManager()">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Slots Status Management</h1>
                <p class="text-sm text-gray-500 mt-1">Drag and drop slots to pause or activate them for bookings.</p>
            </div>
            <!-- Alert Notification -->
            <div x-show="notification.show" 
                 x-transition
                 class="px-4 py-2 rounded-lg text-sm font-medium text-white shadow-lg transition-colors z-50 fixed top-4 right-4"
                 :class="notification.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
                 style="display: none;">
                <span x-text="notification.message"></span>
            </div>
        </div>

        <!-- Tents List -->
        <div class="space-y-8">
            @foreach($tents as $tent)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" :class="darkMode ? 'dark bg-gray-900 border-gray-800' : ''">
                    <!-- Tent Header -->
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50" :class="darkMode ? 'border-gray-800 bg-gray-950' : ''">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-16 overflow-hidden rounded-lg border border-gray-100 bg-gray-100" :class="darkMode ? 'border-gray-800 bg-gray-800' : ''">
                                @if($tent->image)
                                    <img class="h-full w-full object-cover" src="{{ Storage::url($tent->image) }}" alt="">
                                @elseif($tent->images->isNotEmpty())
                                    <img class="h-full w-full object-cover" src="{{ Storage::url($tent->images->first()->image_path) }}" alt="">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">{{ $tent->name }}</h3>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">{{ $tent->slots->count() }} Total Slots</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kanban Board for Slots -->
                    <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x border-gray-100" :class="darkMode ? 'divide-gray-800 border-gray-800' : ''">
                        
                        <!-- Active Slots Column -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-bold flex items-center gap-2 text-green-600">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Active Slots
                                </h4>
                                <span class="bg-green-50 text-green-700 text-xs py-0.5 px-2 rounded-full font-bold count-badge">{{ $tent->slots->filter(fn($s) => $s->pauses->isEmpty())->count() }}</span>
                            </div>
                            <div class="slot-list active-list min-h-[100px] flex flex-col gap-2 p-2 rounded-xl bg-gray-50 border border-dashed border-gray-200" 
                                 :class="darkMode ? 'bg-gray-800/50 border-gray-700' : ''"
                                 data-tent-id="{{ $tent->id }}" data-status="0">
                                @forelse($tent->slots->filter(fn($s) => $s->pauses->isEmpty()) as $slot)
                                    <div class="slot-item cursor-grab active:cursor-grabbing p-3 rounded-lg bg-white border shadow-sm flex items-center justify-between transition-transform hover:scale-[1.02]"
                                         :class="darkMode ? 'bg-gray-900 border-gray-700 text-gray-100' : 'border-gray-200 text-gray-900'"
                                         data-slot-id="{{ $slot->id }}">
                                        <div class="info-wrapper flex flex-col gap-1 w-full">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                                <span class="font-bold text-sm">Slot {{ $slot->tent_number }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-placeholder text-center text-xs text-gray-400 py-4 font-medium uppercase tracking-widest hidden-if-not-empty">Drop here</div>
                                @endforelse
                                <div class="empty-placeholder text-center text-xs text-gray-400 py-4 font-medium uppercase tracking-widest hidden-if-not-empty" style="display: {{ $tent->slots->filter(fn($s) => $s->pauses->isEmpty())->count() ? 'none' : 'block' }}">Drop here</div>
                            </div>
                        </div>

                        <!-- Paused Slots Column -->
                        <div class="p-6 bg-red-50/10" :class="darkMode ? 'bg-red-900/5' : ''">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-bold flex items-center gap-2 text-red-600">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Paused Slots
                                </h4>
                                <span class="bg-red-50 text-red-700 text-xs py-0.5 px-2 rounded-full font-bold count-badge">{{ $tent->slots->filter(fn($s) => $s->pauses->isNotEmpty())->count() }}</span>
                            </div>
                            <div class="slot-list paused-list min-h-[100px] flex flex-col gap-2 p-2 rounded-xl bg-gray-50 border border-dashed border-red-200" 
                                 :class="darkMode ? 'bg-gray-800/50 border-red-900/30' : ''"
                                 data-tent-id="{{ $tent->id }}" data-status="1">
                                @forelse($tent->slots->filter(fn($s) => $s->pauses->isNotEmpty()) as $slot)
                                    <div class="slot-item cursor-grab active:cursor-grabbing p-3 rounded-lg bg-white border border-red-100 shadow-sm flex items-center justify-between transition-transform hover:scale-[1.02]"
                                         :class="darkMode ? 'bg-gray-900 border-red-900/50 text-gray-100' : 'text-gray-900'"
                                         data-slot-id="{{ $slot->id }}">
                                        <div class="info-wrapper flex flex-col gap-1 w-full">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class="font-bold text-sm">Slot {{ $slot->tent_number }}</span>
                                            </div>
                                            @php $p = $slot->pauses->sortBy('end_date')->first(); @endphp
                                            <div class="pause-dates text-[10px] bg-red-100 text-red-700 px-2 py-0.5 rounded font-bold w-fit">
                                                {{ $p ? $p->start_date->format('M d') . ' - ' . $p->end_date->format('M d') : 'Paused' }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-placeholder text-center text-xs text-red-300 py-4 font-medium uppercase tracking-widest hidden-if-not-empty">Drop here to pause</div>
                                @endforelse
                                <div class="empty-placeholder text-center text-xs text-red-300 py-4 font-medium uppercase tracking-widest hidden-if-not-empty" style="display: {{ $tent->slots->filter(fn($s) => $s->pauses->isNotEmpty())->count() ? 'none' : 'block' }}">Drop here to pause</div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Date Modal -->
        <div x-show="modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;">
            <div @click.away="cancelPause()" class="bg-white rounded-2xl shadow-2xl border border-gray-100 w-full max-w-md overflow-hidden transform transition-all p-6" :class="darkMode ? 'bg-gray-900 border-gray-800' : ''">
                <h3 class="text-xl font-bold mb-4" :class="darkMode ? 'text-white' : 'text-gray-900'">Pause Slot</h3>
                <p class="text-sm text-gray-500 mb-6">Select the date range to pause this specific slot.</p>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase mb-1">Start Date</label>
                            <input type="date" x-model="formData.start_date" class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500" :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : ''">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase mb-1">End Date</label>
                            <input type="date" x-model="formData.end_date" class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500" :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : ''">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase mb-1">Reason (Optional)</label>
                        <input type="text" x-model="formData.reason" placeholder="e.g. Maintenance, Private Event" class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500" :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : ''">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8">
                    <button @click="cancelPause()" type="button" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                    <button @click="confirmPause()" type="button" class="px-6 py-2 text-sm font-bold text-white bg-green-600 hover:bg-green-700 shadow flex items-center justify-center rounded-lg transition-colors">Confirm Pause</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Init script -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('slotManager', () => ({
                notification: { show: false, message: '', type: 'success' },
                modalOpen: false,
                pendingAction: null,
                formData: { start_date: '', end_date: '', reason: '' },
                
                init() {
                    const tentGroups = {};
                    
                    // Group lists by tent ID
                    document.querySelectorAll('.slot-list').forEach(list => {
                        const tentId = list.dataset.tentId;
                        if (!tentGroups[tentId]) {
                            tentGroups[tentId] = [];
                        }
                        tentGroups[tentId].push(list);
                    });

                    // Initialize Sortable for each tent group
                    Object.values(tentGroups).forEach(lists => {
                        lists.forEach(list => {
                            new Sortable(list, {
                                group: 'shared-' + list.dataset.tentId,
                                animation: 150,
                                ghostClass: 'opacity-50',
                                onAdd: (evt) => {
                                    const itemEl = evt.item;
                                    const slotId = itemEl.dataset.slotId;
                                    const isPaused = evt.to.dataset.status === '1';

                                    if (!isPaused) {
                                        // Moved from Paused -> Active
                                        this.updateBackend(slotId, 'active', {})
                                            .then(() => {
                                                this.updateCounts(evt);
                                                // Remove date badge
                                                const badge = itemEl.querySelector('.pause-dates');
                                                if (badge) badge.remove();
                                                
                                                // Update Icon color
                                                const icon = itemEl.querySelector('svg');
                                                icon.classList.remove('text-red-400');
                                                icon.classList.add('text-gray-400');
                                                
                                                this.updatePlaceholders(evt.to);
                                                this.updatePlaceholders(evt.from);
                                            })
                                            .catch(() => {
                                                // Rollback visually
                                                evt.from.insertBefore(itemEl, evt.from.children[evt.oldIndex]);
                                                this.updatePlaceholders(evt.to);
                                                this.updatePlaceholders(evt.from);
                                            });
                                    } else {
                                        // Moved from Active -> Paused
                                        this.pendingAction = { evt, slotId, itemEl };
                                        
                                        const now = new Date();
                                        const tomorrow = new Date(now);
                                        tomorrow.setDate(tomorrow.getDate() + 1);
                                        
                                        this.formData.start_date = now.toISOString().split('T')[0];
                                        this.formData.end_date = tomorrow.toISOString().split('T')[0];
                                        this.formData.reason = 'Maintenance';
                                        
                                        this.modalOpen = true;
                                    }
                                },
                                onRemove: (evt) => {
                                   this.updatePlaceholders(evt.from);
                                }
                            });
                        });
                    });
                },
                
                updatePlaceholders(list) {
                    const placeholder = list.querySelector('.empty-placeholder');
                    if (placeholder) {
                        const hasItems = list.querySelectorAll('.slot-item').length > 0;
                        placeholder.style.display = hasItems ? 'none' : 'block';
                    }
                },

                updateCounts(evt) {
                    const toBadge = evt.to.parentElement.querySelector('.count-badge');
                    const fromBadge = evt.from.parentElement.querySelector('.count-badge');
                    if (toBadge) toBadge.innerText = parseInt(toBadge.innerText) + 1;
                    if (fromBadge) fromBadge.innerText = parseInt(fromBadge.innerText) - 1;
                },

                confirmPause() {
                    const { evt, slotId, itemEl } = this.pendingAction;
                    
                    this.updateBackend(slotId, 'pause', this.formData)
                        .then(() => {
                            this.updateCounts(evt);
                            
                            // Format dates simply like "Mar 25"
                            const start = new Date(this.formData.start_date);
                            const end = new Date(this.formData.end_date);
                            const formatter = new Intl.DateTimeFormat('en', { month: 'short', day: 'numeric' });
                            
                            // Visual injection
                            let dateBadge = document.createElement('div');
                            dateBadge.className = 'pause-dates text-[10px] bg-red-100 text-red-700 px-2 py-0.5 rounded font-bold w-fit mt-1';
                            dateBadge.innerText = `${formatter.format(start)} - ${formatter.format(end)}`;
                            
                            itemEl.querySelector('.info-wrapper').appendChild(dateBadge);
                            
                            // Update Icon color
                            const icon = itemEl.querySelector('svg');
                            icon.classList.remove('text-gray-400');
                            icon.classList.add('text-red-400');

                            this.updatePlaceholders(evt.to);
                            this.updatePlaceholders(evt.from);
                            this.modalOpen = false;
                        })
                        .catch(() => {
                            this.cancelPause();
                        });
                },

                cancelPause() {
                    if (this.pendingAction) {
                        const { evt, itemEl } = this.pendingAction;
                        evt.from.insertBefore(itemEl, evt.from.children[evt.oldIndex]);
                        this.updatePlaceholders(evt.from);
                        this.updatePlaceholders(evt.to);
                    }
                    this.modalOpen = false;
                    this.pendingAction = null;
                },

                updateBackend(slotId, action, data) {
                    return axios.post('/admin/slots/update-state', {
                        slot_id: slotId,
                        action: action,
                        start_date: data.start_date,
                        end_date: data.end_date,
                        reason: data.reason
                    }, {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(response => {
                        this.showNotification(response.data.message, 'success');
                        return response;
                    })
                    .catch(error => {
                        this.showNotification(error.response?.data?.message || 'Error updating slot status.', 'error');
                        throw error;
                    });
                },

                showNotification(msg, type) {
                    this.notification.message = msg;
                    this.notification.type = type;
                    this.notification.show = true;
                    setTimeout(() => {
                        this.notification.show = false;
                    }, 3000);
                }
            }));
        });
    </script>
    <style>
        .hidden-if-not-empty { display: none; }
    </style>
</x-admin>
