<div class="content-panel">
    <form method="post" novalidate>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="equipment_id">Equipment</label>
                <select class="form-select" id="equipment_id" name="equipment_id" required>
                    <option value="">Select equipment</option>
                    <?php foreach ($equipmentOptions as $equipment): ?>
                        <option value="<?= (int) $equipment['equipment_id'] ?>" <?= selected((string) $record['equipment_id'], (string) $equipment['equipment_id']) ?>>
                            <?= e($equipment['asset_number'] . ' - ' . $equipment['equipment_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="maintenance_date">Maintenance Date</label>
                <input class="form-control" id="maintenance_date" name="maintenance_date" type="date" value="<?= e($record['maintenance_date']) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="next_schedule">Next Schedule</label>
                <input class="form-control" id="next_schedule" name="next_schedule" type="date" value="<?= e($record['next_schedule']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="technician">Technician</label>
                <input class="form-control" id="technician" name="technician" value="<?= e($record['technician']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="cost">Cost</label>
                <input class="form-control" id="cost" name="cost" type="number" min="0" step="0.01" value="<?= e((string) $record['cost']) ?>">
            </div>
            <div class="col-12">
                <label class="form-label" for="description">Description / Remarks</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= e($record['description']) ?></textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary" type="submit"><i class="bi bi-save me-1"></i>Save</button>
            <a class="btn btn-outline-secondary" href="<?= base_url('maintenance/index.php') ?>">Cancel</a>
        </div>
    </form>
</div>
