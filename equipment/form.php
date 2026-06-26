<?php
$item = $item ?? [
    'asset_number' => '',
    'equipment_name' => '',
    'category_id' => '',
    'brand' => '',
    'model' => '',
    'serial_number' => '',
    'laboratory_room' => '',
    'purchase_date' => '',
    'status' => 'Available',
    'remarks' => '',
    'image_path' => '',
];
?>
<div class="content-panel">
    <form method="post" enctype="multipart/form-data" novalidate>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="asset_number">Asset Number</label>
                <input class="form-control" id="asset_number" name="asset_number" value="<?= e($item['asset_number']) ?>" required>
            </div>
            <div class="col-md-8">
                <label class="form-label" for="equipment_name">Equipment Name</label>
                <input class="form-control" id="equipment_name" name="equipment_name" value="<?= e($item['equipment_name']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="category_id">Category</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Uncategorized</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category['category_id'] ?>" <?= selected((string) $item['category_id'], (string) $category['category_id']) ?>><?= e($category['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="brand">Brand</label>
                <input class="form-control" id="brand" name="brand" value="<?= e($item['brand']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="model">Model</label>
                <input class="form-control" id="model" name="model" value="<?= e($item['model']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="serial_number">Serial Number</label>
                <input class="form-control" id="serial_number" name="serial_number" value="<?= e($item['serial_number']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="laboratory_room">Laboratory Room</label>
                <input class="form-control" id="laboratory_room" name="laboratory_room" value="<?= e($item['laboratory_room']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="purchase_date">Purchase Date</label>
                <input class="form-control" id="purchase_date" name="purchase_date" type="date" value="<?= e($item['purchase_date']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="status">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <?php foreach (status_options() as $option): ?>
                        <option value="<?= e($option) ?>" <?= selected($item['status'], $option) ?>><?= e($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label" for="item_image">Item Picture</label>
                <input class="form-control" id="item_image" name="item_image" type="file" accept="image/jpeg,image/png,image/webp">
                <div class="form-text">Optional. JPG, PNG, or WebP up to 2 MB.</div>
            </div>
            <?php if (!empty($item['image_path'])): ?>
                <div class="col-md-4">
                    <label class="form-label d-block">Current Picture</label>
                    <img class="item-preview" src="<?= base_url($item['image_path']) ?>" alt="<?= e($item['equipment_name']) ?>">
                </div>
            <?php endif; ?>
            <div class="col-12">
                <label class="form-label" for="remarks">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="4"><?= e($item['remarks']) ?></textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Save</button>
            <a class="btn btn-outline-secondary" href="<?= base_url('equipment/list.php') ?>">Cancel</a>
        </div>
    </form>
</div>
