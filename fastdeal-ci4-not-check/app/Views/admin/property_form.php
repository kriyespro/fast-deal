<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php $isEdit = isset($property); ?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-outfit">
            <?= $isEdit ? 'Edit Property: ' . esc($property['title']) : 'Add New Property' ?>
        </h1>
        <p class="text-slate-500 text-sm mt-1">Fill out the details below to publish a listing.</p>
    </div>
    <a href="<?= base_url('admin/listings') ?>"
        class="text-slate-500 hover:text-slate-900 text-sm font-medium flex items-center gap-2">
        <i class="ph-bold ph-arrow-left"></i> Back to Listings
    </a>
</div>

<?php if (session()->has('errors')): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
        <ul class="list-disc list-inside text-sm space-y-1">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= $isEdit ? base_url('admin/listings/update/' . $property['id']) : base_url('admin/listings/store') ?>"
    method="POST" enctype="multipart/form-data" class="space-y-6" id="propertyForm">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- ─── Main Form Column ──────────────────────────────── -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="ph ph-house text-red"></i> Basic Information
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Property Title *</label>
                        <input type="text" name="title" value="<?= old('title', $property['title'] ?? '') ?>"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all"
                            placeholder="e.g. Luxurious Sea-View Apartment in Bandra" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="6"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all resize-none"
                            placeholder="Write a compelling description of the property..."><?= old('description', $property['description'] ?? '') ?></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Price (₹) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">₹</span>
                                <input type="number" step="1" name="price"
                                    value="<?= old('price', $property['price'] ?? '') ?>"
                                    class="w-full border border-slate-300 rounded-lg pl-8 pr-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all"
                                    placeholder="2500000" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Listing Type *</label>
                            <select name="listing_type"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all bg-white" required>
                                <option value="sale" <?= old('listing_type', $property['listing_type'] ?? '') === 'sale' ? 'selected' : '' ?>>For Sale</option>
                                <option value="rent" <?= old('listing_type', $property['listing_type'] ?? '') === 'rent' ? 'selected' : '' ?>>For Rent</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Property Type *</label>
                            <select name="property_type"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all bg-white" required>
                                <?php foreach (['Villa', 'Apartment', 'House', 'Studio', 'Penthouse', 'Bungalow', 'Farmhouse', 'Commercial', 'Plot'] as $pt): ?>
                                    <option value="<?= $pt ?>" <?= old('property_type', $property['property_type'] ?? '') === $pt ? 'selected' : '' ?>><?= $pt ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status *</label>
                            <select name="status"
                                class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all bg-white" required>
                                <?php foreach (['available' => 'Available', 'pending' => 'Pending', 'sold' => 'Sold', 'rented' => 'Rented'] as $v => $l): ?>
                                    <option value="<?= $v ?>" <?= old('status', $property['status'] ?? 'available') === $v ? 'selected' : '' ?>><?= $l ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Details -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="ph ph-list-bullets text-red"></i> Property Details
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1 flex items-center gap-1"><i class="ph ph-bed"></i> Bedrooms</label>
                        <input type="number" name="bedrooms" value="<?= old('bedrooms', $property['bedrooms'] ?? '') ?>"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all"
                            placeholder="e.g. 3" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1 flex items-center gap-1"><i class="ph ph-bathtub"></i> Bathrooms</label>
                        <input type="number" step="0.5" name="bathrooms" value="<?= old('bathrooms', $property['bathrooms'] ?? '') ?>"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all"
                            placeholder="e.g. 2" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1 flex items-center gap-1"><i class="ph ph-square"></i> Area (sqft)</label>
                        <input type="number" name="area_sqft" value="<?= old('area_sqft', $property['area_sqft'] ?? '') ?>"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all"
                            placeholder="e.g. 2500" min="0">
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="ph ph-map-pin text-red"></i> Location
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Street Address</label>
                        <input type="text" name="address" value="<?= old('address', $property['address'] ?? '') ?>"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all"
                            placeholder="e.g. 14 Waroda Road, Bandra West">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">City</label>
                        <input type="text" name="city" value="<?= old('city', $property['city'] ?? '') ?>"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-red/30 focus:border-red outline-none transition-all"
                            placeholder="e.g. Mumbai">
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── Sidebar Column ─────────────────────────────────── -->
        <div class="space-y-6">

            <!-- ══ IMAGE MANAGER ══ -->
            <?php
            // Build full list of existing images for edit mode
            $existingImages = [];
            if ($isEdit) {
                if (!empty($property['main_image'])) {
                    $existingImages[] = ['path' => $property['main_image'], 'is_cover' => true];
                }
                if (!empty($property['gallery_images'])) {
                    $gallery = json_decode($property['gallery_images'], true) ?? [];
                    foreach ($gallery as $g) {
                        $existingImages[] = ['path' => $g, 'is_cover' => false];
                    }
                }
            }
            ?>
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6"
                x-data="imageManager(<?= count($existingImages) ?>)">

                <h2 class="text-lg font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <i class="ph ph-images text-red"></i> Property Images
                </h2>
                <p class="text-xs text-slate-400 mb-4">
                    <?= $isEdit ? 'Manage existing photos below. Add new ones via the upload zone.' : 'First image = main cover photo. Up to 5 images, 5MB each.' ?>
                </p>

                <?php if ($isEdit && !empty($existingImages)): ?>
                <!-- ── Existing Images Grid ────────────────────── -->
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Current Photos
                            <span class="text-slate-400 font-normal normal-case">(<?= count($existingImages) ?> saved)</span>
                        </p>
                        <span class="text-xs text-slate-400">Hover → click 🗑 to remove</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <?php foreach ($existingImages as $idx => $img): ?>
                        <div class="relative group rounded-xl overflow-hidden border border-slate-200 bg-slate-100 aspect-video shadow-sm"
                            x-data="{ marked: false }">

                            <img src="<?= image_url($img['path']) ?>"
                                alt="Photo <?= $idx + 1 ?>"
                                class="w-full h-full object-cover transition-all duration-200"
                                :class="marked ? 'opacity-30 grayscale' : ''">

                            <!-- Cover badge -->
                            <?php if ($img['is_cover']): ?>
                            <span class="absolute top-1.5 left-1.5 bg-red text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow z-10">
                                Cover
                            </span>
                            <?php endif; ?>

                            <!-- Delete overlay -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                <button type="button"
                                    @click="marked = !marked; $dispatch('toggle-delete', {path: '<?= $img['path'] ?>', remove: marked})"
                                    class="w-8 h-8 rounded-full flex items-center justify-center shadow-lg transition-all"
                                    :class="marked ? 'bg-green-500 scale-110' : 'bg-red scale-100'">
                                    <i class="text-white text-sm" :class="marked ? 'ph-bold ph-arrow-u-up-left' : 'ph-bold ph-trash'"></i>
                                </button>
                                <span class="text-white text-[10px] mt-1 bg-black/60 px-2 py-0.5 rounded-full"
                                    x-text="marked ? 'Click to undo' : 'Remove'"></span>
                            </div>

                            <!-- Deleted X overlay -->
                            <div x-show="marked" class="absolute inset-0 bg-red/20 flex items-center justify-center pointer-events-none">
                                <i class="ph-bold ph-x text-red text-3xl opacity-60"></i>
                            </div>

                            <!-- Hidden checkbox: tells backend which images to DELETE -->
                            <input type="checkbox"
                                name="delete_images[]"
                                value="<?= $img['path'] ?>"
                                class="hidden"
                                :checked="marked">
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Deletion summary -->
                    <div x-show="deleteCount > 0" x-transition
                        class="mt-2 flex items-center gap-2 bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 text-orange-800 text-xs">
                        <i class="ph-fill ph-warning text-orange-500"></i>
                        <span x-text="deleteCount + ' image(s) will be removed on save'"></span>
                    </div>
                </div>
                <hr class="border-slate-100 mb-5">
                <?php endif; ?>

                <!-- ── New Image Upload Zone ─────────────────── -->
                <div>
                    <?php if ($isEdit && !empty($existingImages)): ?>
                    <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                        Add More Photos
                        <span class="text-slate-400 font-normal normal-case">(optional)</span>
                    </p>
                    <?php endif; ?>

                    <!-- Drop Zone -->
                    <div class="relative flex flex-col items-center justify-center w-full min-h-[130px] border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200"
                        :class="dragging ? 'border-red bg-red/5 scale-[1.02]' : 'border-slate-300 bg-slate-50 hover:border-red/50 hover:bg-red/5'"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.fileInput.click()">

                        <template x-if="!dragging">
                            <div class="text-center pointer-events-none p-5">
                                <i class="ph ph-upload-simple text-3xl text-slate-300 mb-2 block"></i>
                                <p class="text-sm font-semibold text-slate-600">Click or drag images here</p>
                                <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP — up to <?= $isEdit ? '10' : '5' ?> files, 5MB each</p>
                            </div>
                        </template>
                        <template x-if="dragging">
                            <div class="text-center pointer-events-none p-5">
                                <i class="ph ph-arrow-circle-down text-3xl text-red mb-2 block animate-bounce"></i>
                                <p class="text-sm font-semibold text-red">Drop here!</p>
                            </div>
                        </template>

                        <input type="file" name="images[]" multiple accept="image/*"
                            x-ref="fileInput" class="hidden"
                            @change="handleFiles($event.target.files)">
                    </div>

                    <!-- Confirmation Banner -->
                    <div x-show="files.length > 0" x-transition
                        class="mt-3 flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-2.5 text-green-800 text-sm">
                        <i class="ph-fill ph-check-circle text-green-500 text-lg flex-shrink-0"></i>
                        <span class="font-semibold" x-text="files.length + ' new image' + (files.length > 1 ? 's' : '') + ' ready to upload'"></span>
                        <button type="button" @click="clearNewFiles()"
                            class="ml-auto text-green-600 hover:text-red transition-colors" title="Clear new uploads">
                            <i class="ph ph-x-circle text-lg"></i>
                        </button>
                    </div>

                    <!-- New Image Preview Grid -->
                    <div x-show="previews.length > 0" class="mt-3 space-y-2" x-transition>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">
                            New Uploads Preview
                            <span class="text-slate-400 normal-case font-normal">
                                (<?= $isEdit ? 'will be added to existing' : 'first = cover photo' ?>)
                            </span>
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="(preview, index) in previews" :key="index">
                                <div class="relative group rounded-xl overflow-hidden border border-green-200 bg-slate-100 aspect-video shadow-sm hover:shadow-md transition-shadow ring-1 ring-green-300">
                                    <img :src="preview.url" :alt="'New ' + (index + 1)" class="w-full h-full object-cover">

                                    <!-- Cover badge (only for create mode first image) -->
                                    <?php if (!$isEdit): ?>
                                    <span x-show="index === 0"
                                        class="absolute top-1.5 left-1.5 bg-red text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow">
                                        Cover
                                    </span>
                                    <?php else: ?>
                                    <span class="absolute top-1.5 left-1.5 bg-green-600 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow">
                                        New
                                    </span>
                                    <?php endif; ?>

                                    <span class="absolute bottom-1.5 left-1.5 bg-black/50 text-white text-[10px] px-1.5 py-0.5 rounded-full"
                                        x-text="preview.size"></span>

                                    <button type="button"
                                        @click.stop="removeNewFile(index)"
                                        class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-red text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow">
                                        <i class="ph-bold ph-x text-xs"></i>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <!-- File name list -->
                        <div class="space-y-1">
                            <template x-for="(preview, index) in previews" :key="'nm' + index">
                                <div class="flex items-center gap-2 text-xs text-slate-500 bg-green-50 rounded-lg px-3 py-1.5 border border-green-100">
                                    <i class="ph-fill ph-image text-green-400"></i>
                                    <span class="truncate" x-text="preview.name"></span>
                                    <span class="ml-auto text-slate-400 shrink-0" x-text="preview.size"></span>
                                    <i class="ph-fill ph-check-circle text-green-500"></i>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div><!-- end image manager -->

            <!-- Features -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="ph ph-star text-red"></i> Features &amp; Amenities
                </h2>
                <?php
                $existingFeatures = [];
                if ($isEdit && $property['features']) {
                    $existingFeatures = json_decode($property['features'], true) ?? [];
                }
                $availableFeatures = [
                    'Swimming Pool', 'Garage / Parking', 'Smart Home', 'Security System',
                    'Landscaped Garden', 'Gym / Fitness', 'Elevator / Lift', 'Central AC',
                    'Power Backup', 'Solar Panels', 'CCTV Cameras', 'Furnished',
                    'Sea View', 'Home Theater', 'Modular Kitchen',
                ];
                ?>
                <div class="grid grid-cols-1 gap-2.5">
                    <?php foreach ($availableFeatures as $feature): ?>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="features[]" value="<?= $feature ?>"
                                <?= in_array($feature, $existingFeatures) ? 'checked' : '' ?>
                                class="w-4 h-4 text-red accent-red rounded border-slate-300 focus:ring-red/30">
                            <span class="text-sm text-slate-600 group-hover:text-slate-900 transition-colors"><?= $feature ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Submit -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <button type="submit"
                    class="w-full bg-red hover:bg-[#a0211b] text-white py-3.5 rounded-xl font-bold transition-all shadow-lg shadow-red/20 flex items-center justify-center gap-2 mb-3 hover:scale-[1.02] active:scale-95">
                    <i class="ph-bold <?= $isEdit ? 'ph-pencil-simple' : 'ph-rocket-launch' ?>"></i>
                    <?= $isEdit ? 'Update Property' : 'Publish Property' ?>
                </button>
                <a href="<?= base_url('admin/listings') ?>"
                    class="block w-full text-center py-3 border border-slate-200 rounded-xl text-slate-600 font-medium hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

<script>
function imageManager(existingCount) {
    return {
        files: [],
        previews: [],
        dragging: false,
        deleteSet: new Set(),
        deleteCount: 0,
        maxFiles: 10,
        maxSizeMB: 5,

        init() {
            // Listen for toggle-delete events bubbled from individual image cards
            this.$el.addEventListener('toggle-delete', (e) => {
                const { path, remove } = e.detail;
                if (remove) {
                    this.deleteSet.add(path);
                } else {
                    this.deleteSet.delete(path);
                }
                this.deleteCount = this.deleteSet.size;

                // Sync all delete checkboxes so they submit correctly
                this.$el.querySelectorAll('input[name="delete_images[]"]').forEach(cb => {
                    cb.checked = this.deleteSet.has(cb.value);
                });
            });
        },

        handleFiles(newFiles) {
            const arr = Array.from(newFiles);
            const remaining = this.maxFiles - this.files.length;
            if (remaining <= 0) {
                alert(`Maximum ${this.maxFiles} new images at a time.`);
                return;
            }
            const toAdd = arr.slice(0, remaining);
            const big   = toAdd.filter(f => f.size > this.maxSizeMB * 1024 * 1024);
            if (big.length) alert(`Skipped ${big.length} file(s) over ${this.maxSizeMB}MB.`);

            const valid = toAdd.filter(f => f.size <= this.maxSizeMB * 1024 * 1024);
            valid.forEach(file => {
                this.files.push(file);
                const reader = new FileReader();
                reader.onload = e => {
                    this.previews.push({
                        url: e.target.result,
                        name: file.name,
                        size: this.formatSize(file.size)
                    });
                };
                reader.readAsDataURL(file);
            });
            this.$nextTick(() => this.syncInput());
        },

        handleDrop(e) {
            this.dragging = false;
            if (e.dataTransfer.files.length) this.handleFiles(e.dataTransfer.files);
        },

        removeNewFile(index) {
            this.files.splice(index, 1);
            this.previews.splice(index, 1);
            this.$nextTick(() => this.syncInput());
        },

        clearNewFiles() {
            this.files = [];
            this.previews = [];
            this.$refs.fileInput.value = '';
        },

        syncInput() {
            const dt = new DataTransfer();
            this.files.forEach(f => dt.items.add(f));
            this.$refs.fileInput.files = dt.files;
        },

        formatSize(bytes) {
            if (bytes < 1024) return bytes + 'B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + 'KB';
            return (bytes / (1024 * 1024)).toFixed(1) + 'MB';
        }
    }
}
</script>

<?= $this->endSection() ?>